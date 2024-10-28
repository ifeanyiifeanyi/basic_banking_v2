<?php
// BankService.php
namespace App\Services;

use App\Models\Bank;
use Illuminate\Support\Facades\DB;
// THIS SERVICE IS FOR CREATING NEW BANK, AND THE BANK TRANSACTION REQUIREMENTS
class BankService
{
    public function getActiveBanks()
    {
        return Bank::where('is_active', true)
            ->with(['requirements' => function ($query) {
                $query->orderBy('order');
            }])
            ->get()
            ->map(function ($bank) {
                return [
                    'id' => $bank->id,
                    'name' => $bank->name,
                    'code' => $bank->code,
                    'swift_code' => $bank->swift_code,
                    'requirements' => $bank->requirements->map(function ($req) {
                        return [
                            'name' => $req->field_name,
                            'type' => $req->field_type,
                            'options' => $req->field_options,
                            'required' => $req->is_required,
                            'description' => $req->description,
                            'placeholder' => $req->placeholder,
                            'validation_rules' => $req->validation_rules,
                            'order' => $req->order
                        ];
                    })
                ];
            });
    }

    
    public function createBank(array $data)
    {
        return DB::transaction(function () use ($data) {
            $bank = Bank::create($data);

            if (isset($data['requirements'])) {
                $this->updateRequirements($bank, $data['requirements']);
            }

            return $bank;
        });
    }

    public function updateBank(Bank $bank, array $data)
    {
        return DB::transaction(function () use ($bank, $data) {
            $bank->update($data);

            if (isset($data['requirements'])) {
                $this->updateRequirements($bank, $data['requirements']);
            }

            return $bank;
        });
    }

    public function deleteBank(Bank $bank)
    {
        return DB::transaction(function () use ($bank) {
            $bank->requirements()->delete();
            $bank->delete();
        });
    }


    protected function updateRequirements(Bank $bank, array $requirements)
    {
        $bank->requirements()->delete();

        $requirementsData = collect($requirements)->map(function ($requirement, $index) {
            // Handle field options for select type
            if ($requirement['field_type'] === 'select' && isset($requirement['field_options'])) {
                // Convert textarea lines to array
                $options = array_filter(explode("\n", $requirement['field_options']));
                $requirement['field_options'] = $options;
            } else {
                $requirement['field_options'] = null;
            }

            return $requirement + ['order' => $index];
        })->all();

        $bank->requirements()->createMany($requirementsData);
    }
}
