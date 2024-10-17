<?php

namespace App\Services;

use App\Models\User;
use App\Models\KycDocument;
use App\Models\KycQuestion;
use App\Models\KycResponse;
use Illuminate\Support\Facades\Storage;

class KycResponseService
{
    public function getQuestionsForUser(User $user)
    {
        // Implementation to get questions and any existing responses for the user
    }

    public function submitResponses(User $user, array $data)
    {
        foreach ($data['responses'] as $questionId => $response) {
            $question = KycQuestion::findOrFail($questionId);

            $kycResponse = KycResponse::updateOrCreate(
                ['user_id' => $user->id, 'kyc_question_id' => $questionId],
                ['text_response' => $question->response_type === 'text' ? $response : null]
            );

            if ($question->response_type === 'file' || $question->response_type === 'multiple_files') {
                $this->handleFileUploads($kycResponse, $response);
            }
        }
    }

    private function handleFileUploads(KycResponse $kycResponse, $files)
    {
        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            $path = Storage::disk('public')->put('kyc_documents', $file);

            KycDocument::create([
                'kyc_response_id' => $kycResponse->id,
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'original_filename' => $file->getClientOriginalName(),
            ]);
        }
    }
}
