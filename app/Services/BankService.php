<?php
// BankService.php
namespace App\Services;

use App\Models\Bank;
use Illuminate\Support\Facades\DB;
// THIS SERVICE IS FOR CREATING NEW BANK, AND THE BANK TRANSACTION REQUIREMENTS
class BankService
{
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
