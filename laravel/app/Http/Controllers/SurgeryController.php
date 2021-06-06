<?php

namespace App\Http\Controllers\COMPANY;

use Image;
use Notification;
use App\Notifications\NewSurgeryNotification;
use App\Notifications\UpdateSurgeryNotification;
use App\Notifications\CanceledSurgeryCompanyNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Surgery;
use App\Models\SurgeryAddress;
use App\Models\SurgeryMaterial;
use App\Models\State;
use App\Models\SpecialtySubgroup;

use App\Models\User;

class SurgeryController extends Controller
{

    private function translate () {
        return [
            'status' => [
                'Aguardando início' => 'bg-warning',
                'Em andamento'      => 'bg-info',
                'Concluído'         => 'bg-success',
                'Cancelado'         => 'bg-danger',
            ]
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        $company = $user->company;
        $surgeries = Surgery::where('company_id', $company->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        $vars = [];
        $vars['user'] = $user;
        $vars['company'] = $company;
        $vars['surgeries'] = $surgeries;
        $vars['translate'] = $this->translate();

        return view('company.surgery.index', $vars);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $vars = [];
        $vars['user'] = Auth::user();
        $vars['states'] = State::all();
        $vars['specialties'] = SpecialtySubgroup::all();

        return view('company.surgery.create', $vars);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'specialty'     => ['required', function ($attribute, $value, $fail) {
                helperValidateSpecialtySubgroup($attribute, $value, $fail);
            }],
            'patient_name'      => ['required', 'max:190'],
            'start_date'        => ['required', 'date_format:d/m/Y H:i:s', function ($attribute, $value, $fail) {
                return helperValidateDateAfterNow($attribute, $value, $fail, 'd/m/Y H:i:s', 'A data de início não pode ser inferior a data atual.');
            }],
            'description'       => ['required', 'max:190'],
            'material_file'     => ['file'],
            'material_name'     => ['required'],
            'material_name.*'   => ['required', 'max:190'],
            'material_amount'   => ['required'],
            'material_amount.*' => ['required', 'numeric'],
            'address_name'      => ['required', 'max:190'],
            'zip_code'          => ['required', 'max:190'],
            'address'           => ['required', 'max:190'],
            'number'            => ['required', 'numeric'],
            'state'             => ['required', function ($attribute, $value, $fail) {
                helperValidateState($attribute, $value, $fail);
            }],
            'city' => ['required', function ($attribute, $value, $fail) {
                helperValidateCity($attribute, $value, $fail);
            }],
            'complement'    => ['max:190'],
            'image'         => ['image'],
        ]);

        // Check instrumentalist exists
        $instrumentalists = helperGetInstrumentalistsToSurgery($validated['city'], $validated['specialty']);

        if (empty($instrumentalists) || !count($instrumentalists)):
            return redirect()
                ->back()
                ->withInput()
                ->with('error', ['Desculpa, não foi encontrado instrumentadores para o local.']);
        endif;

        // Save surgery
        $user = Auth::user();
        $surgeryFill = [
            'company_id'            => $user->company->id,
            'specialty_subgroup_id' => $validated['specialty'],
            'patient_name'          => $validated['patient_name'],
            'start_date'            => helperChangeDateFormat($validated['start_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s'),
            'description'           => $validated['description'],
            'status'                => 'Aguardando início',
        ];
        
        $surgeryDB = Surgery::create($surgeryFill);
        
        if (!$surgeryDB):
            return redirect()
                ->back()
                ->with('error', ['Desculpa, não foi possível cadastrar cirúrgia.']);
        endif;
        
        if (isset($validated['material_file']) && !empty($validated['material_file'])):
            $materialUpload = $validated['material_file'];
            $materialFileName = time().'.'.$materialUpload->extension();
            $materialPath = 'uploads/'.$user->id.'/surgeries/'.$surgeryDB->id.'/materials';
            $materialFullPath = $materialPath.'/'.$materialFileName;
            Storage::disk('public')->put($materialFullPath, file_get_contents($materialUpload));
            
            $surgeryDB->material_file = 'storage/'.$materialFullPath;
            $surgeryDB->save();
        endif;

        // Save surgery Address
        $surgeryAddressFill = [
            'surgery_id'    => $surgeryDB->id,
            'name'          => $validated['address_name'],
            'zip_code'      => $validated['zip_code'],
            'address'       => $validated['address'],
            'number'        => $validated['number'],
            'state_id'      => $validated['state'],
            'city_id'       => $validated['city'],
        ];

        if (isset($validated['complement']) && !empty($validated['complement'])):
            $surgeryAddressFill['complement'] = $validated['complement'];
        endif;

        if (!empty($request->file('image'))):
            $imageUpload = $request->file('image');
            $addressImageFileName = time().'.'.$imageUpload->extension();

            $addressImagePath = 'uploads/'.$user->id.'/surgeries/'.$surgeryDB->id.'/address';
            $addressImageFullPath = $addressImagePath.'/'.$addressImageFileName;
            $addressImageUpload = Image::make($imageUpload->getRealPath())->encode();
            Storage::disk('public')->put($addressImageFullPath, $addressImageUpload);

            $surgeryAddressFill['image'] = 'storage/'.$addressImageFullPath;
        endif;

        $surgeryAddressDB = SurgeryAddress::create($surgeryAddressFill);
    
        if (!$surgeryAddressDB):
            return redirect()
                ->back()
                ->with('error', ['Desculpa, não foi possível cadastrar endereço da cirúrgia.']);
        endif;

        foreach ($validated['material_name'] as $key => $material):
            SurgeryMaterial::create([
                'surgery_id'    => $surgeryDB->id,
                'name'          => $material,
                'amount'        => $validated['material_amount'][$key]
            ]);
        endforeach;

        // Call instrumentalists
        foreach ($instrumentalists as $instrumentalist):
            $surgeryDB->notifiedInstrumentalist()->attach($instrumentalist->id);
            
            // Notification
            Notification::send($instrumentalist->user, new NewSurgeryNotification($surgeryDB));
        endforeach;

        return redirect()
            ->route('company.surgery.show', ['surgery' => $surgeryDB->id])
            ->with('success', ['Cirúrgia cadastrada com sucesso.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $vars = [];
        $vars['user'] = Auth::user();
        $vars['surgery'] = $vars['user']->company->surgeries->find($id);
        $vars['translate'] = $this->translate();

        if (!$vars['surgery']):
            return redirect()
                ->route('company.surgery.index')
                ->with('error', ['Cirúrgia não encontrada.']);
        endif;

        return view('company.surgery.show', $vars);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $vars = [];
        $vars['user'] = Auth::user();
        $vars['surgery'] = $vars['user']->company->surgeries->find($id);

        if (!$vars['surgery']):
            return redirect()
                ->back()
                ->with('error', ['Cirúrgia não encontrada.']);
        endif;

        if ($vars['surgery']->status != "Aguardando início"):
            return redirect()
                ->back()
                ->with('error', ['A cirúrgia não pode ser editada.']);
        endif;

        return view('company.surgery.edit', $vars);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validated = $request->validate([
            'start_date'        => ['required', 'date_format:d/m/Y H:i:s', function ($attribute, $value, $fail) {
                return helperValidateDateAfterNow($attribute, $value, $fail, 'd/m/Y H:i:s', 'A data de início não pode ser inferior a data atual.');
            }],
            'description'       => ['required', 'max:190'],
            'image'         => ['image'],
        ]);

        $user = Auth::user();
        $surgery = $user->company->surgeries->find($id);

        if (!$surgery):
            return redirect()
                ->route('company.surgery.index')
                ->with('error', ['Cirúrgia não encontrada.']);
        endif;

        if ($surgery->status != "Aguardando início"):
            return redirect()
                ->back()
                ->with('error', ['A cirúrgia não pode ser editada.']);
        endif;

        // Update surgery
        if (isset($validated['start_date']) && !empty($validated['start_date'])):
            $surgery->start_date = helperChangeDateFormat($validated['start_date'], 'd/m/Y H:i:s', 'Y-m-d H:i:s');
        endif;

        if (isset($validated['description']) && !empty($validated['description'])):
            $surgery->description = $validated['description'];
        endif;
        
        $surgeryDB = $surgery->save();
        
        if (!$surgeryDB):
            return redirect()
                ->back()
                ->with('error', ['Desculpa, não foi possível atualizar cirúrgia.']);
        endif;

        // Update surgery Address
        if (!empty($request->file('image'))):
            $imageUpload = $request->file('image');
            $addressImageFileName = time().'.'.$imageUpload->extension();

            $addressImagePath = 'uploads/'.$user->id.'/surgeries/'.$surgery->id.'/address';
            $addressImageFullPath = $addressImagePath.'/'.$addressImageFileName;
            $addressImageUpload = Image::make($imageUpload->getRealPath())->encode();
            Storage::disk('public')->put($addressImageFullPath, $addressImageUpload);

            $surgery->surgeryAddress->image = 'storage/'.$addressImageFullPath;
        endif;

        $surgeryAddressDB = $surgery->surgeryAddress->save();
    
        if (!$surgeryAddressDB):
            return redirect()
                ->back()
                ->with('error', ['Desculpa, não foi possível cadastrar endereço da cirúrgia.']);
        endif;

        $instrumentalist = $surgery->instrumentalist;
        if ($instrumentalist):
            // Notification
            Notification::send($instrumentalist->user, new UpdateSurgeryNotification($surgery));
        endif;

        return redirect()
            ->route('company.surgery.show', ['surgery' => $surgery->id])
            ->with('success', ['Cirúrgia atualizada com sucesso.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $validated = $request->validate([
            'reason_cancellation' => ['required', 'max:190'],
        ]);

        $user = Auth::user();
        $company = $user->company;
        $surgery = $company->surgeries->find($id);

        if (!$surgery):
            return redirect()
                ->route('company.surgery.index')
                ->with('error', ['Cirúrgia não encontrada.']);
        endif;

        if ($surgery->status != "Aguardando início"):
            return redirect()
                ->route('company.surgery.index')
                ->with('error', ['Cirúrgia não pode ser cancelada.']);
        endif;

        // check punishment
        helperPunishmentCompany();

        // Clean instrumentalist
        $surgery
            ->notifiedInstrumentalist()
            ->newPivotStatement()
            ->where('surgery_id', $surgery->id)
            ->update([
                'expired' => 1
            ]);

        $surgery->reason_cancellation = $validated['reason_cancellation'];
        $surgery->status = "Cancelado";
        
        if ($surgery->save()):
            
            $instrumentalist = $surgery->instrumentalist;
            if ($instrumentalist):
                // Notification
                Notification::send($instrumentalist->user, new CanceledSurgeryCompanyNotification($surgery));
            endif;

            return redirect()
                ->route('company.surgery.index')
                ->with('success', ['Cirúrgia cancelada com sucesso.']);
        endif;

        return redirect()
                ->route('company.surgery.index')
                ->with('error', ['Desculpa, não foi possível cancelar cirúrgia.']);
    }

    /**
     * Rating the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rating(Request $request, $id)
    {
        //
        $validated = $request->validate([
            'rating'    => ['required', 'numeric', 'min:1', 'max:5'],
            'observation'   => ['max:190'],
        ]);

        //
        $user =  Auth::user();
        $surgery = $user->company->surgeries->find($id);

        if (
            $surgery && 
            $surgery->status == "Concluído" && 
            $surgery->instrumentalist_rating == 0
        ):
            
            $surgery->instrumentalist_rating = $request->input('rating');
            
            if (isset($validated['observation']) && !empty($validated['observation'])):
                $surgery->company_observation = $validated['observation'];
            endif;

            if ($surgery->save()):
                return redirect()
                    ->route('company.surgery.index')
                    ->with('success', ['Cirúrgia avaliada com sucesso.']);
            endif;
        endif;

        return redirect()
            ->back()
            ->with('error', ['Desculpa, não foi possível avaliar cirúrgia.']);
    }
}
