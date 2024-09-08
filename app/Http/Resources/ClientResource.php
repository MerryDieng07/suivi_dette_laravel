<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        // $photoBase64 = null;

        // if ($this->photo) {
        //     $imagePath = storage_path('app/public/' . $this->photo); // Chemin vers l'image
        //     if (file_exists($imagePath)) {
        //         $imageData = file_get_contents($imagePath);
        //         $photoBase64 = 'data:image/jpeg;base64,' . base64_encode($imageData); // Assurez-vous que le type MIME est correct
        //     }
        // }

        return [
            'nom' => $this->surname,
            'user' => new UserResource($this->whenLoaded('user')),
            'photo' => $this->user->photo,
        ];
    }
}
