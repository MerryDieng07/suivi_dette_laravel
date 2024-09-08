<?php

namespace App\Http\Controllers;

use App\Enums\StateEnum;
use App\Traits\RestResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\User;
use App\Http\Requests\StoreClientRequest;
use App\Http\Resources\ClientCollection;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ClientResource;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\QueryException;
use App\Facades\PdfFacade;
use App\Facades\QrcodeFacade;
use App\Mail\FidelityCardMail;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{
    use RestResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $include = $request->has('include') ? [$request->input('include')] : [];

        $query = QueryBuilder::for(Client::class)
            ->allowedFilters(['surname'])
            ->allowedIncludes($include)
            ->whereNotNull('user_id');

        if ($request->has('active')) {
            $active = $request->input('active');
            if ($active === 'oui') {
                $query->whereHas('user', function($q) {
                    $q->where('active', true);
                });
            } elseif ($active === 'non') {
                $query->whereHas('user', function($q) {
                    $q->where('active', false);
                });
            }
        }

        $clients = $query->get();

        return $this->sendResponse(new ClientCollection($clients));
    }

    public function store(StoreClientRequest $request)
    
{
$data=$request->validated();


    $clientRequest = $request->only('surname', 'adresse', 'telephone');
   




    try {
        DB::beginTransaction();

        $clientRequest = $request->only('surname', 'address', 'telephone', 'user');
        

        $client = Client::create($clientRequest);


        $userRequest =$data['user'];
        if ($request->has('user')) {
            $user = User::create($userRequest);
            //     'nom' => $request->input('user.nom'),
            //     'prenom' => $request->input('user.prenom'),
            //     'login' => $request->input('user.login'),
            //     'password' => bcrypt($request->password),
            //     'role_id' => $request->input('user.role_id'),
            //     'etat' => $request->input('user.etat') ?? 'ACTIF',
            //     'photo' => $request->input('user.photo'),
            // ]);
        }
        

        if ($user) {
            $client->user()->associate($user);
            $client->save();
        }


        $user->client()->save($client);
    
        $qrCodeData = $client->telephone;
            
       
        $qrCodeFileName = 'client_' . $client->id . '.png';
        $qrCodePath = QrcodeFacade::generateQrCode($qrCodeData, $qrCodeFileName);

        Mail::to($client->user->login)->send(new FidelityCardMail($client, $qrCodePath));
      
       

        
        
       /*  $pdfPath = storage_path('public/pdfs/client_' . $client->id . '.pdf');
        PdfFacade::generatePdf('pdf.client', ['client' => $client, 'qrCodePath' => $qrCodePath], $pdfPath); */
        // Génération du QR code
        // $qrCode = QrCode::format('png')->size(200)->generate(route('client.show', ['id' => $client->id]));
        // Storage::put('qrcodes/'. $client->id. '.png', (string) $qrCode);

        // // Génération du PDF
        // $view = 'pdf.client';
        // $data = ['client' => $client, 'user' => $user];
        // $filePath = storage_path('app/public/pdfs/'. $client->id. '.pdf');
        // PdfFacade::generatePdf($view, $data, $filePath);

        // Génération de la carte de fidélité
        // $this->generateFidelityCard($user);

        DB::commit();

        return $this->sendResponse(new ClientResource($client), StateEnum::SUCCESS);
       
        // Génération de la carte de fidélité
        // $this->generateFidelityCard($user);


    } catch (QueryException $e) {
        DB::rollBack();
        return $this->sendErrorResponse('Database error: ' . $e->getMessage(), StateEnum::ECHEC);
    } catch (\Exception $e) {
        DB::rollBack();
        return $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), StateEnum::ECHEC);
    }
}



    public function show(string $id): JsonResponse
    {
        $client = Client::find($id);

        if (!$client) {
            return $this->sendErrorResponse('Client not found', StateEnum::ECHEC);
        }

        return $this->sendResponse(new ClientResource($client), StateEnum::SUCCESS);
    }

    public function update(StoreClientRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $client = Client::findOrFail($id);
            
            $clientData = $request->only('surname', 'address', 'telephone');
            $client->update($clientData);

            if ($request->has('user')) {
                $user = $client->user;
                if ($user) {
                    $userData = $request->input('user');
                    $user->update([
                        'nom' => $userData['nom'] ?? $user->nom,
                        'prenom' => $userData['prenom'] ?? $user->prenom,
                        'login' => $userData['login'] ?? $user->login,
                        'password' => $userData['password'] ?? $user->password,
                        'role' => $userData['role'] ?? $user->role,
                    ]);
                }
            }

            DB::commit();
            return $this->sendResponse(new ClientResource($client), StateEnum::SUCCESS);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), StateEnum::ECHEC);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $client = Client::findOrFail($id);

            if ($client->user) {
                $client->user->delete();
            }

            $client->delete();

            DB::commit();
            return $this->sendResponse(null, StateEnum::SUCCESS);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendErrorResponse('An error occurred: ' . $e->getMessage(), StateEnum::ECHEC);
        }
    }

    public function clientByTelephone(Request $request)
    {
        $telephone = $request->input('telephone');
        // dd($telephone);
    
        // Recherchez un client par son numéro de téléphone
        $client = Client::where('telephone', $telephone)->first();
    
        if (!$client) {
            return $this->sendErrorResponse('Client not found', StateEnum::ECHEC);
        }
    if($client->user){
        $client->user->photo = $client->user->photo;
        $client->user->role = $client->user->role_id;
        $client->user->etat = $client->user->etat;
    }
    $imagePath = storage_path('app/public/' . $client->user->photo); // Chemin vers l'image, ajustez selon votre configuration
    if (file_exists($imagePath)) {
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData);
        $client->user->photo = 'data:image/jpeg;base64,' . $base64Image; // Assurez-vous de définir le bon type MIME
    } else {
        $client->photo = null;
    }

        return $this->sendResponse(new ClientResource($client), StateEnum::SUCCESS);
    }
    

    public function findByTelephone($telephone): JsonResponse
    {
        $client = Client::where('telephone', $telephone)->first();

        if (!$client) {
            return response()->json([
                'message' => 'Client not found'
            ], 404);
        }

        return response()->json($client);

        // Si le client a une image associée
        if ($client->photo) {
            // Lire le fichier image
            $imagePath = storage_path('app/public/' . $client->photo); // Chemin vers l'image, ajustez selon votre configuration
            if (file_exists($imagePath)) {
                $imageData = file_get_contents($imagePath);
                $base64Image = base64_encode($imageData);
                $client->photo = 'data:image/jpeg;base64,' . $base64Image; // Assurez-vous de définir le bon type MIME
            } else {
                $client->photo = null;
            }
        }

        return $this->sendResponse(new ClientResource($client), StateEnum::SUCCESS);
    }

  /*   public function sendFidelityCard($clientId)
    {
        // Récupérez le client et le fichier de la carte de fidélité
        $client = Client::find($clientId);
        $filePath = storage_path('app/public/qrcodes/client_' . $clientId . '.png');

        // Envoyer l'e-mail
        Mail::to($client->email)->send(new FidelityCardMail($filePath));

        return response()->json(['status' => 'success', 'message' => 'E-mail envoyé avec succès.']);
    } */

    }

    



