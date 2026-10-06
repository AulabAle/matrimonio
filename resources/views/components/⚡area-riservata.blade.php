<?php
 
use Livewire\Component;
use App\Models\PersonalRecord;
use App\Models\User;
use App\Models\Selfie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
 
new class extends Component
{
    public $activeTab = 'accounts'; // accounts, rsvp, map
    public $expandedRsvpId = null;
    public $selectedTableId = null;
    public $guestSearch = '';
 
    // Guest editing variables
    public $editingGuestId = null;
    public $editingGuestType = null; // 'primary' or 'member'
    public $editFirstName = '';
    public $editLastName = '';
    public $editTableNumber = null;
    public $editWillAttend = true;
    public $editIsPregnant = false;
    public $editAllergies = '';
    public $editDietaryRequirements = '';
    public $editNotes = '';
    public $editMemberType = '';
    public $editAge = '';
    public $editNeedsHighchair = false;
    public $editNeedsBabyMenu = false;
    public $editHasGift = false;
    public $editReceivesFavor = false;
 
    public $tables = [
        // Left column (Bride - Sposa)
        ['id' => 3, 'x' => 170, 'y' => 120, 'side' => 'sposa'],
        ['id' => 1, 'x' => 270, 'y' => 120, 'side' => 'sposa'],
        ['id' => 5, 'x' => 170, 'y' => 200, 'side' => 'sposa'],
        ['id' => 7, 'x' => 270, 'y' => 200, 'side' => 'sposa'],
        ['id' => 9, 'x' => 170, 'y' => 280, 'side' => 'sposa'],
        ['id' => 11, 'x' => 270, 'y' => 280, 'side' => 'sposa'],
        ['id' => 15, 'x' => 170, 'y' => 360, 'side' => 'sposa'],
        ['id' => 19, 'x' => 270, 'y' => 360, 'side' => 'sposa'],
        ['id' => 21, 'x' => 170, 'y' => 440, 'side' => 'sposa'],
        ['id' => 23, 'x' => 270, 'y' => 440, 'side' => 'sposa'],
        ['id' => 25, 'x' => 170, 'y' => 520, 'side' => 'sposa'],
        ['id' => 27, 'x' => 270, 'y' => 520, 'side' => 'sposa'],
        ['id' => 29, 'x' => 170, 'y' => 600, 'side' => 'sposa'],
        ['id' => 31, 'x' => 270, 'y' => 600, 'side' => 'sposa'],
        ['id' => 33, 'x' => 170, 'y' => 680, 'side' => 'sposa'],
        ['id' => 35, 'x' => 270, 'y' => 680, 'side' => 'sposa'],
 
        // Right column (Groom - Sposo)
        ['id' => 2, 'x' => 490, 'y' => 120, 'side' => 'sposo'],
        ['id' => 4, 'x' => 590, 'y' => 120, 'side' => 'sposo'],
        ['id' => 6, 'x' => 490, 'y' => 200, 'side' => 'sposo'],
        ['id' => 8, 'x' => 590, 'y' => 200, 'side' => 'sposo'],
        ['id' => 10, 'x' => 490, 'y' => 280, 'side' => 'sposo'],
        ['id' => 12, 'x' => 590, 'y' => 280, 'side' => 'sposo'],
        ['id' => 14, 'x' => 490, 'y' => 360, 'side' => 'sposo'],
        ['id' => 16, 'x' => 590, 'y' => 360, 'side' => 'sposo'],
        ['id' => 18, 'x' => 490, 'y' => 420, 'side' => 'sposo'],
        ['id' => 20, 'x' => 590, 'y' => 420, 'side' => 'sposo'],
        ['id' => 22, 'x' => 490, 'y' => 500, 'side' => 'sposo'],
        ['id' => 24, 'x' => 590, 'y' => 500, 'side' => 'sposo'],
        ['id' => 26, 'x' => 590, 'y' => 580, 'side' => 'sposo'],
 
        // Center table (Sposi)
        ['id' => 'sposi', 'x' => 380, 'y' => 120, 'side' => 'sposi'],
    ];
 
    // Form field for selected guest in table assigner
    public $assigneeGuestType = '';
    public $assigneeGuestId = '';
     
    // Form fields
    public $selectedUserId = null;
    public $fullName = '';
    public $phone = '';
    public $confirmedSeats = 1;
    public $notes = '';
     
    public $isEditing = false;
    public $editingRecordId = null;
    public $showForm = false;
 
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->to('/login');
        }
    }
 
    #[Computed]
    public function records()
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }
 
        if ($user->isAdmin()) {
            return PersonalRecord::with('user')->get()->toArray();
        }
 
        $record = PersonalRecord::where('user_id', $user->id)->first();
        return $record ? [$record->toArray()] : [];
    }
 
    #[Computed]
    public function allUsers()
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return [];
        }
 
        return User::where('role', '!=', 'admin')->get()->toArray();
    }
 
    #[Computed]
    public function rsvps()
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return [];
        }
 
        return \App\Models\Rsvp::with('members')->orderBy('created_at', 'desc')->get()->toArray();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        if (!Auth::user()->isAdmin()) {
            $this->selectedUserId = Auth::id();
        }
    }

    public function resetForm()
    {
        $this->fullName = '';
        $this->phone = '';
        $this->confirmedSeats = 1;
        $this->notes = '';
        $this->editingRecordId = null;
        $this->selectedUserId = Auth::user()->isAdmin() ? null : Auth::id();
        $this->isEditing = false;
    }

    public function save()
    {
        $user = Auth::user();
        
        // Strict Validation
        $rules = [
            'fullName' => 'required|string|min:3|max:100',
            'phone' => 'nullable|string|max:30',
            'confirmedSeats' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500',
        ];

        if ($user->isAdmin()) {
            $rules['selectedUserId'] = 'required|exists:users,id';
        }

        $this->validate($rules);

        $targetUserId = $user->isAdmin() ? $this->selectedUserId : $user->id;

        if ($this->isEditing) {
            // Check authorization to prevent IDOR (Insecure Direct Object Reference)
            $record = PersonalRecord::find($this->editingRecordId);
            if (!$record) {
                return;
            }
            if (!$user->isAdmin() && $record->user_id !== $user->id) {
                abort(403, 'Azione non autorizzata.');
            }

            $record->update([
                'full_name' => $this->fullName,
                'phone' => $this->phone,
                'confirmed_seats' => $this->confirmedSeats,
                'notes' => $this->notes,
                'user_id' => $targetUserId,
            ]);
        } else {
            // Check if user already has a record
            if (!$user->isAdmin()) {
                $existing = PersonalRecord::where('user_id', $user->id)->first();
                if ($existing) {
                    $this->addError('fullName', 'Hai già inserito i tuoi dati. Puoi modificarli anziché crearne di nuovi.');
                    return;
                }
            } else {
                $existing = PersonalRecord::where('user_id', $targetUserId)->first();
                if ($existing) {
                    $this->addError('selectedUserId', 'Questo utente ha già dei dati inseriti.');
                    return;
                }
            }

            PersonalRecord::create([
                'user_id' => $targetUserId,
                'full_name' => $this->fullName,
                'phone' => $this->phone,
                'confirmed_seats' => $this->confirmedSeats,
                'notes' => $this->notes,
            ]);
        }

        $this->resetForm();
        $this->showForm = false;
        session()->flash('message', 'Dati salvati con successo in modo sicuro.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $record = PersonalRecord::find($id);

        if (!$record) {
            return;
        }

        // Strict authorization check
        if (!$user->isAdmin() && $record->user_id !== $user->id) {
            abort(403, 'Azione non autorizzata.');
        }

        $this->editingRecordId = $record->id;
        $this->selectedUserId = $record->user_id;
        $this->fullName = $record->full_name;
        $this->phone = $record->phone;
        $this->confirmedSeats = $record->confirmed_seats;
        $this->notes = $record->notes;
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function delete($id)
    {
        $user = Auth::user();
        $record = PersonalRecord::find($id);

        if (!$record) {
            return;
        }

        // Strict authorization check
        if (!$user->isAdmin() && $record->user_id !== $user->id) {
            abort(403, 'Azione non autorizzata.');
        }

        $record->delete();
        
        session()->flash('message', 'Dati eliminati con successo.');
    }

    public function deleteRsvp($id)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Azione non autorizzata.');
        }

        $rsvp = \App\Models\Rsvp::find($id);
        if ($rsvp) {
            $rsvp->delete();
            session()->flash('message', 'Partecipazione RSVP eliminata con successo.');
        }
    }

    public function toggleRsvpExpansion($id)
    {
        if ($this->expandedRsvpId === $id) {
            $this->expandedRsvpId = null;
        } else {
            $this->expandedRsvpId = $id;
        }
    }

    public function assignToTable($typeOrTableId, $id = null, $tableId = null)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Azione non autorizzata.');
        }

        $type = null;

        if ($id === null) {
            // Called from the form with only tableId as parameter
            $targetTableId = $typeOrTableId;
            if (empty($this->assigneeGuestId)) {
                return;
            }
            if (str_contains($this->assigneeGuestId, '-')) {
                list($type, $id) = explode('-', $this->assigneeGuestId);
            }
        } else {
            // Called with three arguments: assignToTable($type, $id, $tableId)
            $type = $typeOrTableId;
            $targetTableId = $tableId;
        }

        if ($type === 'primary') {
            $rsvp = \App\Models\Rsvp::find($id);
            if ($rsvp) {
                $rsvp->update(['table_number' => $targetTableId]);
            }
        } elseif ($type === 'member') {
            $member = \App\Models\RsvpMember::find($id);
            if ($member) {
                $member->update(['table_number' => $targetTableId]);
            }
        }

        // Reset the selection properties
        $this->assigneeGuestId = '';
        $this->assigneeGuestType = '';
    }

    public function removeFromTable($type, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Azione non autorizzata.');
        }

        if ($type === 'primary') {
            $rsvp = \App\Models\Rsvp::find($id);
            if ($rsvp) {
                $rsvp->update(['table_number' => null]);
            }
        } elseif ($type === 'member') {
            $member = \App\Models\RsvpMember::find($id);
            if ($member) {
                $member->update(['table_number' => null]);
            }
        }

    }

    public function getGuestsData()
    {
        $attending = [];
        $unassigned = [];
        $assignedToSelected = [];

        foreach ($this->rsvps as $rsvp) {
            // Check primary guest
            if ($rsvp['will_attend']) {
                $guest = [
                    'id' => $rsvp['id'],
                    'type' => 'primary',
                    'name' => $rsvp['first_name'] . ' ' . $rsvp['last_name'],
                    'is_child' => false,
                    'is_pregnant' => $rsvp['is_pregnant'],
                    'allergies' => $rsvp['allergies'],
                    'dietary_requirements' => $rsvp['dietary_requirements'],
                    'notes' => $rsvp['notes'],
                    'table_number' => $rsvp['table_number'],
                ];

                if (empty($rsvp['table_number'])) {
                    $unassigned[] = $guest;
                } elseif ($rsvp['table_number'] == $this->selectedTableId) {
                    $assignedToSelected[] = $guest;
                }
                $attending[] = $guest;
            }

            // Check members
            foreach ($rsvp['members'] as $member) {
                if ($member['will_attend']) {
                    $guest = [
                        'id' => $member['id'],
                        'type' => 'member',
                        'name' => $member['first_name'] . ' ' . $member['last_name'] . ' (' . ($member['member_type'] === 'child' ? 'Figlio' : ($member['member_type'] === 'spouse' ? 'Coniuge' : 'Accompagnatore')) . ')',
                        'is_child' => $member['member_type'] === 'child',
                        'is_pregnant' => $member['is_pregnant'],
                        'age' => $member['age'],
                        'needs_highchair' => $member['needs_highchair'],
                        'needs_baby_menu' => $member['needs_baby_menu'],
                        'allergies' => $member['allergies'],
                        'dietary_requirements' => $member['dietary_requirements'],
                        'notes' => $member['notes'],
                        'table_number' => $member['table_number'],
                    ];

                    if (empty($member['table_number'])) {
                        $unassigned[] = $guest;
                    } elseif ($member['table_number'] == $this->selectedTableId) {
                        $assignedToSelected[] = $guest;
                    }
                    $attending[] = $guest;
                }
            }
        }

        return [
            'attending' => $attending,
            'unassigned' => $unassigned,
            'assignedToSelected' => $assignedToSelected,
        ];
    }

    public function getAllGuestsForTable()
    {
        $all = [];
        foreach ($this->rsvps as $rsvp) {
            // Primary guest
            $all[] = [
                'id' => $rsvp['id'],
                'type' => 'primary',
                'first_name' => $rsvp['first_name'],
                'last_name' => $rsvp['last_name'],
                'rsvp_type' => $rsvp['rsvp_type'],
                'member_type' => 'primary',
                'will_attend' => (bool)$rsvp['will_attend'],
                'is_pregnant' => (bool)$rsvp['is_pregnant'],
                'allergies' => $rsvp['allergies'],
                'dietary_requirements' => $rsvp['dietary_requirements'],
                'notes' => $rsvp['notes'],
                'table_number' => $rsvp['table_number'],
                'age' => null,
                'needs_highchair' => false,
                'needs_baby_menu' => false,
                'has_gift' => (bool)($rsvp['has_gift'] ?? false),
                'receives_favor' => (bool)($rsvp['receives_favor'] ?? false),
            ];

            // Members
            foreach ($rsvp['members'] as $member) {
                $all[] = [
                    'id' => $member['id'],
                    'type' => 'member',
                    'first_name' => $member['first_name'],
                    'last_name' => $member['last_name'],
                    'rsvp_type' => $rsvp['rsvp_type'],
                    'member_type' => $member['member_type'], // spouse, companion, child
                    'will_attend' => (bool)$member['will_attend'],
                    'is_pregnant' => (bool)$member['is_pregnant'],
                    'allergies' => $member['allergies'],
                    'dietary_requirements' => $member['dietary_requirements'],
                    'notes' => $member['notes'],
                    'table_number' => $member['table_number'],
                    'age' => $member['age'],
                    'needs_highchair' => (bool)$member['needs_highchair'],
                    'needs_baby_menu' => (bool)$member['needs_baby_menu'],
                    'has_gift' => (bool)($member['has_gift'] ?? false),
                    'receives_favor' => (bool)($member['receives_favor'] ?? false),
                ];
            }
        }
        
        // Filter by search query if set
        if (!empty($this->guestSearch)) {
            $query = strtolower($this->guestSearch);
            $all = array_filter($all, function($g) use ($query) {
                return str_contains(strtolower($g['first_name']), $query) || 
                       str_contains(strtolower($g['last_name']), $query);
            });
        }

        return $all;
    }

    public function startEditGuest($type, $id)
    {
        $this->editingGuestType = $type;
        $this->editingGuestId = $id;

        if ($type === 'primary') {
            $rsvp = \App\Models\Rsvp::find($id);
            if ($rsvp) {
                $this->editFirstName = $rsvp->first_name;
                $this->editLastName = $rsvp->last_name;
                $this->editTableNumber = $rsvp->table_number;
                $this->editWillAttend = (bool)$rsvp->will_attend;
                $this->editIsPregnant = (bool)$rsvp->is_pregnant;
                $this->editAllergies = $rsvp->allergies;
                $this->editDietaryRequirements = $rsvp->dietary_requirements;
                $this->editNotes = $rsvp->notes;
                $this->editHasGift = (bool)$rsvp->has_gift;
                $this->editReceivesFavor = (bool)$rsvp->receives_favor;
                
                // reset member specific fields
                $this->editMemberType = '';
                $this->editAge = '';
                $this->editNeedsHighchair = false;
                $this->editNeedsBabyMenu = false;
            }
        } elseif ($type === 'member') {
            $member = \App\Models\RsvpMember::find($id);
            if ($member) {
                $this->editFirstName = $member->first_name;
                $this->editLastName = $member->last_name;
                $this->editTableNumber = $member->table_number;
                $this->editWillAttend = (bool)$member->will_attend;
                $this->editIsPregnant = (bool)$member->is_pregnant;
                $this->editAllergies = $member->allergies;
                $this->editDietaryRequirements = $member->dietary_requirements;
                $this->editNotes = $member->notes;
                $this->editHasGift = (bool)$member->has_gift;
                $this->editReceivesFavor = (bool)$member->receives_favor;
                
                $this->editMemberType = $member->member_type;
                $this->editAge = $member->age;
                $this->editNeedsHighchair = (bool)$member->needs_highchair;
                $this->editNeedsBabyMenu = (bool)$member->needs_baby_menu;
            }
        }
    }

    public function cancelEditGuest()
    {
        $this->editingGuestId = null;
        $this->editingGuestType = null;
    }

    public function saveGuest()
    {
        $rules = [
            'editFirstName' => 'required|string|min:2|max:100',
            'editLastName' => 'required|string|min:2|max:100',
            'editTableNumber' => 'nullable|string|max:10', // can be 'sposi' or numeric
            'editWillAttend' => 'required|boolean',
            'editIsPregnant' => 'required|boolean',
            'editAllergies' => 'nullable|string|max:1000',
            'editDietaryRequirements' => 'nullable|string|max:1000',
            'editNotes' => 'nullable|string|max:1000',
            'editHasGift' => 'required|boolean',
            'editReceivesFavor' => 'required|boolean',
        ];

        if ($this->editingGuestType === 'member') {
            $rules['editMemberType'] = 'required|in:spouse,companion,child';
            if ($this->editMemberType === 'child') {
                $rules['editAge'] = 'required|integer|min:0|max:17';
                $rules['editNeedsHighchair'] = 'required|boolean';
                $rules['editNeedsBabyMenu'] = 'required|boolean';
            }
        }

        $this->validate($rules);

        // Map table number properly
        $tableNum = $this->editTableNumber;
        if ($tableNum === '' || $tableNum === 'null' || $tableNum === null) {
            $tableNum = null;
        }

        if ($this->editingGuestType === 'primary') {
            $rsvp = \App\Models\Rsvp::find($this->editingGuestId);
            if ($rsvp) {
                $rsvp->update([
                    'first_name' => $this->editFirstName,
                    'last_name' => $this->editLastName,
                    'table_number' => $tableNum,
                    'will_attend' => $this->editWillAttend,
                    'is_pregnant' => $this->editIsPregnant,
                    'allergies' => $this->editAllergies,
                    'dietary_requirements' => $this->editDietaryRequirements,
                    'notes' => $this->editNotes,
                    'has_gift' => $this->editHasGift,
                    'receives_favor' => $this->editReceivesFavor,
                ]);
            }
        } elseif ($this->editingGuestType === 'member') {
            $member = \App\Models\RsvpMember::find($this->editingGuestId);
            if ($member) {
                $updateData = [
                    'first_name' => $this->editFirstName,
                    'last_name' => $this->editLastName,
                    'table_number' => $tableNum,
                    'will_attend' => $this->editWillAttend,
                    'is_pregnant' => $this->editIsPregnant,
                    'allergies' => $this->editAllergies,
                    'dietary_requirements' => $this->editDietaryRequirements,
                    'notes' => $this->editNotes,
                    'member_type' => $this->editMemberType,
                    'has_gift' => $this->editHasGift,
                    'receives_favor' => $this->editReceivesFavor,
                ];

                if ($this->editMemberType === 'child') {
                    $updateData['age'] = $this->editAge;
                    $updateData['needs_highchair'] = $this->editNeedsHighchair;
                    $updateData['needs_baby_menu'] = $this->editNeedsBabyMenu;
                } else {
                    $updateData['age'] = null;
                    $updateData['needs_highchair'] = false;
                    $updateData['needs_baby_menu'] = false;
                }

                $member->update($updateData);
            }
        }

        $this->cancelEditGuest();
        session()->flash('message', 'Dati dell\'invitato aggiornati con successo.');
    }

    public function downloadExcel()
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Azione non autorizzata.');
        }

        $templatePath = base_path('storage/Diposizione Tavoli NOMI_COGNOMI_INTOLLERANZE.xlsx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template Excel non trovato.');
        }

        $tempDir = storage_path('app/temp_xlsx_' . uniqid());
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($templatePath) !== TRUE) {
            abort(500, 'Impossibile aprire il template Excel.');
        }
        $zip->extractTo($tempDir);
        $zip->close();

        $sheetFile = $tempDir . '/xl/worksheets/sheet1.xml';
        if (!file_exists($sheetFile)) {
            abort(500, 'Struttura template Excel non valida.');
        }

        $dom = new DOMDocument();
        $dom->load($sheetFile);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('ns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        $rsvpsData = $this->rsvps;
        $guestsByTable = [];
        
        foreach ($rsvpsData as $rsvp) {
            if ($rsvp['will_attend']) {
                $table = $rsvp['table_number'];
                if ($table !== null) {
                    $guestsByTable[$table][] = [
                        'first_name' => $rsvp['first_name'],
                        'last_name' => $rsvp['last_name'],
                        'is_child' => false,
                        'needs_highchair' => false,
                        'needs_baby_menu' => false,
                        'allergies' => $rsvp['allergies'],
                        'dietary_requirements' => $rsvp['dietary_requirements'],
                    ];
                }
            }
            foreach ($rsvp['members'] as $m) {
                if ($m['will_attend']) {
                    $table = $m['table_number'];
                    if ($table !== null) {
                        $guestsByTable[$table][] = [
                            'first_name' => $m['first_name'],
                            'last_name' => $m['last_name'],
                            'is_child' => $m['member_type'] === 'child',
                            'needs_highchair' => (bool)$m['needs_highchair'],
                            'needs_baby_menu' => (bool)$m['needs_baby_menu'],
                            'allergies' => $m['allergies'],
                            'dietary_requirements' => $m['dietary_requirements'],
                        ];
                    }
                }
            }
        }

        $tableRowMap = [
            1 => 1, 3 => 18, 5 => 35, 7 => 52, 9 => 68, 11 => 85,
            15 => 102, 19 => 119, 21 => 136, 23 => 164, 25 => 181,
            27 => 198, 29 => 215, 31 => 243, 33 => 260, 35 => 277,
            2 => 294, 4 => 323, 6 => 340, 8 => 357, 10 => 374,
            12 => 401, 14 => 418, 16 => 435, 18 => 452, 20 => 480,
            22 => 497, 24 => 514, 26 => 531
        ];

        $updateCell = function($cellRef, $text, $isNumeric = false) use ($dom, $xpath) {
            $cellNodes = $xpath->query("//ns:c[@r='$cellRef']");
            if ($cellNodes->length > 0) {
                $cellNode = $cellNodes->item(0);
                while ($cellNode->hasChildNodes()) {
                    $cellNode->removeChild($cellNode->firstChild);
                }
                if ($isNumeric) {
                    $cellNode->removeAttribute('t');
                    $vNode = $dom->createElement('v', htmlspecialchars($text));
                    $cellNode->appendChild($vNode);
                } else {
                    $cellNode->setAttribute('t', 'inlineStr');
                    $isNode = $dom->createElement('is');
                    $tNode = $dom->createElement('t', htmlspecialchars($text));
                    $isNode->appendChild($tNode);
                    $cellNode->appendChild($isNode);
                }
            }
        };

        if (!empty($guestsByTable['sposi'])) {
            $srcStartRow = 531;
            $destStartRow = 548;
            $newTitle = 'TAVOLO SPOSI';
            
            $sheetDataNode = $xpath->query("//ns:sheetData")->item(0);
            if ($sheetDataNode) {
                for ($i = 0; $i < 15; $i++) {
                    $srcRowIndex = $srcStartRow + $i;
                    $destRowIndex = $destStartRow + $i;
                    
                    $srcRowNodes = $xpath->query("//ns:row[@r='$srcRowIndex']");
                    if ($srcRowNodes->length > 0) {
                        $srcRowNode = $srcRowNodes->item(0);
                        $destRowNode = $srcRowNode->cloneNode(true);
                        $destRowNode->setAttribute('r', $destRowIndex);
                        
                        $cellNodes = $xpath->query("descendant::ns:c", $destRowNode);
                        foreach ($cellNodes as $cellNode) {
                            $cellRef = $cellNode->getAttribute('r');
                            preg_match('/^[A-Z]+/', $cellRef, $matches);
                            $colLetter = $matches[0];
                            $cellNode->setAttribute('r', $colLetter . $destRowIndex);
                            
                            if ($i >= 2 && $i <= 13) {
                                while ($cellNode->hasChildNodes()) {
                                    $cellNode->removeChild($cellNode->firstChild);
                                }
                                $cellNode->removeAttribute('t');
                            }
                        }
                        
                        if ($i === 0) {
                            $aCellNode = $xpath->query("descendant::ns:c[@r='A$destRowIndex']", $destRowNode)->item(0);
                            if ($aCellNode) {
                                while ($aCellNode->hasChildNodes()) {
                                    $aCellNode->removeChild($aCellNode->firstChild);
                                }
                                $aCellNode->setAttribute('t', 'inlineStr');
                                $isNode = $dom->createElement('is');
                                $tNode = $dom->createElement('t', htmlspecialchars($newTitle));
                                $isNode->appendChild($tNode);
                                $aCellNode->appendChild($isNode);
                            }
                        }
                        
                        if ($i === 14) {
                            $aCellNode = $xpath->query("descendant::ns:c[@r='A$destRowIndex']", $destRowNode)->item(0);
                            if ($aCellNode) {
                                while ($aCellNode->hasChildNodes()) {
                                    $aCellNode->removeChild($aCellNode->firstChild);
                                }
                                $aCellNode->setAttribute('t', 'inlineStr');
                                $isNode = $dom->createElement('is');
                                $tNode = $dom->createElement('t', 'TOTALE: 0 ADULTI');
                                $isNode->appendChild($tNode);
                                $aCellNode->appendChild($isNode);
                            }
                        }
                        
                        $sheetDataNode->appendChild($destRowNode);
                    }
                }
                $tableRowMap['sposi'] = 548;
            }
        }

        foreach ($guestsByTable as $tableNum => $tableGuests) {
            if (!isset($tableRowMap[$tableNum])) {
                continue;
            }

            $startRow = $tableRowMap[$tableNum];

            usort($tableGuests, function($a, $b) {
                return $a['is_child'] <=> $b['is_child'];
            });

            $adults = 0;
            $babies = 0;
            $highchairs = 0;

            $guestCount = count($tableGuests);
            for ($i = 0; $i < 12; $i++) {
                $rowNum = $startRow + 2 + $i;
                if ($i < $guestCount) {
                    $g = $tableGuests[$i];
                    
                    $updateCell("A$rowNum", mb_strtoupper($g['last_name']));
                    $updateCell("C$rowNum", mb_strtoupper($g['first_name']));
                    
                    if ($g['is_child'] || $g['needs_baby_menu']) {
                        $updateCell("E$rowNum", 'X');
                        $babies++;
                    } else {
                        $updateCell("E$rowNum", '');
                        $adults++;
                    }

                    if ($g['needs_highchair']) {
                        $updateCell("F$rowNum", 'X');
                        $highchairs++;
                    } else {
                        $updateCell("F$rowNum", '');
                    }

                    $allergies = trim(($g['allergies'] ?? '') . ' ' . ($g['dietary_requirements'] ?? ''));
                    $updateCell("G$rowNum", $allergies);
                } else {
                    $updateCell("A$rowNum", '');
                    $updateCell("C$rowNum", '');
                    $updateCell("E$rowNum", '');
                    $updateCell("F$rowNum", '');
                    $updateCell("G$rowNum", '');
                }
            }

            $totalRow = $startRow + 14;
            $totalStr = "TOTALE: $adults ADULTI";
            if ($babies > 0) {
                $totalStr .= " + $babies BAMB";
            }
            if ($highchairs > 0) {
                $totalStr .= " + $highchairs SEGG";
            }
            $updateCell("A$totalRow", $totalStr);
        }

        $dom->save($sheetFile);

        $outputPath = storage_path('app/Disposizione_Tavoli_' . uniqid() . '.xlsx');
        $outZip = new ZipArchive();
        if ($outZip->open($outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            abort(500, 'Impossibile salvare il file Excel generato.');
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($tempDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDir) + 1);
                $relativePath = str_replace('\\', '/', $relativePath);
                $outZip->addFile($filePath, $relativePath);
            }
        }
        $outZip->close();

        $deleteDir = function($dir) use (&$deleteDir) {
            if (!is_dir($dir)) return;
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? $deleteDir("$dir/$file") : unlink("$dir/$file");
            }
            rmdir($dir);
        };
        $deleteDir($tempDir);

        return response()->download($outputPath, 'Disposizione_Tavoli.xlsx')->deleteFileAfterSend(true);
    }

    public function exportGuestsCsv()
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Azione non autorizzata.');
        }

        $guests = $this->getAllGuestsForTable();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Lista_Invitati.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($guests) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel to detect encoding correctly
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write CSV headers
            fputcsv($file, [
                'Cognome',
                'Nome',
                'Ruolo',
                'Tavolo',
                'Presenza',
                'Gravidanza',
                'Allergie / Intolleranze',
                'Scelte Alimentari',
                'Età (Bambino)',
                'Seggiolone (Bambino)',
                'Menu Baby (Bambino)',
                'Regalo Ricevuto',
                'Riceve Bomboniera',
                'Note'
            ], ';');

            foreach ($guests as $g) {
                // Determine role string
                $roleStr = '';
                if ($g['member_type'] === 'primary') {
                    $roleStr = 'Capogruppo';
                } elseif ($g['member_type'] === 'spouse') {
                    $roleStr = 'Partner';
                } elseif ($g['member_type'] === 'companion') {
                    $roleStr = 'Accompagnatore';
                } elseif ($g['member_type'] === 'child') {
                    $roleStr = 'Figlio/a';
                }

                // Table number
                $tableStr = $g['table_number'] ?: 'Non assegnato';
                if ($tableStr === 'sposi') {
                    $tableStr = 'Sposi';
                }

                fputcsv($file, [
                    $g['last_name'],
                    $g['first_name'],
                    $roleStr,
                    $tableStr,
                    $g['will_attend'] ? 'Presente' : 'Assente',
                    $g['is_pregnant'] ? 'Sì' : 'No',
                    $g['allergies'] ?: '',
                    $g['dietary_requirements'] ?: '',
                    $g['member_type'] === 'child' ? $g['age'] : '',
                    $g['member_type'] === 'child' ? ($g['needs_highchair'] ? 'Sì' : 'No') : '',
                    $g['member_type'] === 'child' ? ($g['needs_baby_menu'] ? 'Sì' : 'No') : '',
                    $g['has_gift'] ? 'Sì' : 'No',
                    $g['receives_favor'] ? 'Sì' : 'No',
                    $g['notes'] ?: ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadAllPolaroids()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Azione non autorizzata.');
        }

        $selfies = Selfie::orderBy('created_at', 'desc')->get();

        if ($selfies->isEmpty()) {
            session()->flash('message', 'Nessuna foto disponibile nella Zona Selfie.');
            return;
        }

        $tempFolder = storage_path('app/temp_polaroids_' . Str::uuid());
        if (!is_dir($tempFolder)) {
            mkdir($tempFolder, 0755, true);
        }

        $fontPath = 'C:/Windows/Fonts/georgia.ttf';
        if (!file_exists($fontPath)) {
            $fontPath = 'C:/Windows/Fonts/arial.ttf';
        }

        $totalCount = $selfies->count();

        foreach ($selfies as $index => $selfie) {
            $fullPath = null;
            if (str_starts_with($selfie->image_path, 'selfies/')) {
                $fullPath = Storage::disk('public')->path($selfie->image_path);
            } elseif (file_exists(public_path(ltrim($selfie->image_path, '/')))) {
                $fullPath = public_path(ltrim($selfie->image_path, '/'));
            } elseif (file_exists(storage_path('app/public/' . ltrim($selfie->image_path, '/')))) {
                $fullPath = storage_path('app/public/' . ltrim($selfie->image_path, '/'));
            }

            if (!$fullPath || !file_exists($fullPath)) {
                continue;
            }

            $canvasWidth = 1200;
            $canvasHeight = 1440;
            $canvas = imagecreatetruecolor($canvasWidth, $canvasHeight);

            $bgColor = imagecolorallocate($canvas, 253, 251, 247);
            $borderColor = imagecolorallocate($canvas, 209, 178, 128);
            $innerBorderColor = imagecolorallocate($canvas, 229, 231, 235);
            $goldDark = imagecolorallocate($canvas, 140, 109, 59);
            $subTextColor = imagecolorallocate($canvas, 113, 113, 122);

            imagefilledrectangle($canvas, 0, 0, $canvasWidth, $canvasHeight, $bgColor);
            imagesetthickness($canvas, 4);
            imagerectangle($canvas, 10, 10, $canvasWidth - 11, $canvasHeight - 11, $borderColor);

            $imgInfo = @getimagesize($fullPath);
            if (!$imgInfo) {
                imagedestroy($canvas);
                continue;
            }

            $mime = $imgInfo['mime'];
            $srcImg = null;
            switch ($mime) {
                case 'image/jpeg':
                    $srcImg = @imagecreatefromjpeg($fullPath);
                    break;
                case 'image/png':
                    $srcImg = @imagecreatefrompng($fullPath);
                    break;
                case 'image/webp':
                    $srcImg = @imagecreatefromwebp($fullPath);
                    break;
                case 'image/gif':
                    $srcImg = @imagecreatefromgif($fullPath);
                    break;
            }

            if (!$srcImg) {
                imagedestroy($canvas);
                continue;
            }

            if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
                @$exif = exif_read_data($fullPath);
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $srcImg = imagerotate($srcImg, 180, 0);
                            break;
                        case 6:
                            $srcImg = imagerotate($srcImg, -90, 0);
                            break;
                        case 8:
                            $srcImg = imagerotate($srcImg, 90, 0);
                            break;
                    }
                }
            }

            $origW = imagesx($srcImg);
            $origH = imagesy($srcImg);
            $boxX = 70;
            $boxY = 70;
            $boxSize = 1060;

            $srcAspect = $origW / $origH;
            if ($srcAspect > 1) {
                $cropH = $origH;
                $cropW = $origH;
                $cropX = (int)(($origW - $origH) / 2);
                $cropY = 0;
            } else {
                $cropW = $origW;
                $cropH = $origW;
                $cropX = 0;
                $cropY = (int)(($origH - $origW) / 2);
            }

            imagecopyresampled($canvas, $srcImg, $boxX, $boxY, $cropX, $cropY, $boxSize, $boxSize, $cropW, $cropH);
            imagedestroy($srcImg);

            imagesetthickness($canvas, 2);
            imagerectangle($canvas, $boxX - 1, $boxY - 1, $boxX + $boxSize, $boxY + $boxSize, $innerBorderColor);

            if (!empty($selfie->caption)) {
                $fontSize = 32;
                $bbox = imagettfbbox($fontSize, 0, $fontPath, '"' . $selfie->caption . '"');
                $textWidth = abs($bbox[2] - $bbox[0]);
                $textX = max(40, (int)(($canvasWidth - $textWidth) / 2));
                $textY = 1240;
                imagettftext($canvas, $fontSize, 0, $textX, $textY, $goldDark, $fontPath, '"' . $selfie->caption . '"');
            }

            $footerFontSize = 18;
            $dateStr = $selfie->created_at ? $selfie->created_at->format('d/m/Y H:i') : date('d/m/Y H:i');
            $footerText = "Ricordo N° " . ($totalCount - $index) . " • " . $dateStr;
            $fBbox = imagettfbbox($footerFontSize, 0, $fontPath, $footerText);
            $fWidth = abs($fBbox[2] - $fBbox[0]);
            $fX = (int)(($canvasWidth - $fWidth) / 2);
            $fY = 1350;

            imagettftext($canvas, $footerFontSize, 0, $fX, $fY, $subTextColor, $fontPath, $footerText);

            $fileName = sprintf("Polaroid_Selfie_%03d.jpg", $totalCount - $index);
            imagejpeg($canvas, $tempFolder . '/' . $fileName, 90);
            imagedestroy($canvas);
        }

        $zipFileName = 'Selfies_Polaroid_Monica_ed_Erasmo_' . date('Y-m-d_H-i') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $files = glob($tempFolder . '/*.jpg');
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        array_map('unlink', glob($tempFolder . '/*.*'));
        @rmdir($tempFolder);

        if (!file_exists($zipPath)) {
            session()->flash('message', 'Impossibile creare il pacchetto ZIP.');
            return;
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
};
?>

<div class="w-full py-4 space-y-6">
    <!-- Header Card -->
    <div class="bg-white p-2.5 border border-[#D1B280]/40 rounded-sm shadow-xl relative w-full mx-auto transition-all duration-300">
        <div class="border border-[#D1B280]/60 p-6 sm:p-8 relative paper-texture flex flex-col sm:flex-row justify-between items-center gap-4 overflow-hidden">
            <div class="text-center sm:text-left relative z-10">
                <span class="font-script text-gold-dark text-4xl select-none">Area Riservata</span>
                <p class="font-serif text-[10px] uppercase tracking-[0.2em] text-charcoal-light font-semibold mt-1">
                    Benvenuto, {{ Auth::user()->username }} (Ruolo: {{ Auth::user()->isAdmin() ? 'Amministratore' : 'Invitato' }})
                </p>
            </div>
            
            <div class="flex gap-3 relative z-10">
                @if(count($this->records) === 0 && !Auth::user()->isAdmin())
                    <button wire:click="openCreateForm" class="px-4 py-2 bg-sage-dark hover:bg-sage-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-md shadow-md active:scale-95 transition-all cursor-pointer">
                        Inserisci Dati
                    </button>
                @endif

                @if(Auth::user()->isAdmin())
                    <button type="button" 
                            wire:click="downloadAllPolaroids" 
                            wire:loading.attr="disabled"
                            title="Scarica tutti i selfie impaginati in formato Polaroid (ZIP)"
                            class="px-4 py-2 bg-gold-dark hover:bg-gold-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-md shadow-md active:scale-95 transition-all flex items-center gap-2 cursor-pointer border border-gold-medium/30">
                        <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span wire:loading.remove wire:target="downloadAllPolaroids">Scarica Selfie (ZIP Polaroid)</span>
                        <span wire:loading wire:target="downloadAllPolaroids" class="flex items-center gap-1.5">
                            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Generazione...
                        </span>
                    </button>
                    <button wire:click="openCreateForm" class="px-4 py-2 bg-sage-dark hover:bg-sage-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-md shadow-md active:scale-95 transition-all cursor-pointer">
                        Aggiungi Record
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-md text-sm font-serif flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Form Section -->
    @if($showForm)
        <div class="bg-white p-2.5 border border-[#D1B280]/40 rounded-sm shadow-xl relative w-full mx-auto">
            <div class="border border-[#D1B280]/60 p-6 sm:p-8 relative paper-texture">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-serif text-lg text-charcoal font-semibold tracking-wide">
                        {{ $isEditing ? 'Modifica Dati Personali' : 'Inserisci Dati Personali' }}
                    </h3>
                    <span class="text-[10px] uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 font-semibold flex items-center gap-1 select-none">
                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Protetto da crittografia a riposo
                    </span>
                </div>

                <form wire:submit="save" class="space-y-4">
                    @if(Auth::user()->isAdmin())
                        <div>
                            <label for="selectedUserId" class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold mb-1.5">
                                Assegna all'Invitato (User)
                            </label>
                            <select id="selectedUserId" wire:model="selectedUserId" class="w-full px-4 py-2 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md text-sm text-charcoal" required>
                                <option value="">Seleziona un utente...</option>
                                @foreach($this->allUsers as $u)
                                    <option value="{{ $u['id'] }}">{{ $u['username'] }}</option>
                                @endforeach
                            </select>
                            @error('selectedUserId')
                                <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fullName" class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold mb-1.5">
                                Nome e Cognome
                            </label>
                            <input type="text" id="fullName" wire:model="fullName" 
                                   class="w-full px-4 py-2 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md text-sm text-charcoal"
                                   placeholder="Nome Completo dell'Invitato" required>
                            @error('fullName')
                                <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold mb-1.5">
                                Numero di Telefono (Cifrato)
                            </label>
                            <input type="text" id="phone" wire:model="phone" 
                                   class="w-full px-4 py-2 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md text-sm text-charcoal"
                                   placeholder="Es. 3471234567">
                            @error('phone')
                                <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="confirmedSeats" class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold mb-1.5">
                                Numero Posti Confermati
                            </label>
                            <input type="number" id="confirmedSeats" wire:model="confirmedSeats" 
                                   class="w-full px-4 py-2 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md text-sm text-charcoal"
                                   min="1" max="10" required>
                            @error('confirmedSeats')
                                <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold mb-1.5">
                                Esigenze Alimentari / Note (Cifrato)
                            </label>
                            <textarea id="notes" wire:model="notes" rows="2"
                                      class="w-full px-4 py-2 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md text-sm text-charcoal"
                                      placeholder="Allergie, intolleranze o preferenze di seduta..."></textarea>
                            @error('notes')
                                <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="resetForm(); $set('showForm', false);" class="px-4 py-2 border border-zinc-300 text-zinc-700 font-serif text-xs uppercase tracking-wider rounded-md hover:bg-zinc-50 active:scale-95 transition-all cursor-pointer">
                            Annulla
                        </button>
                        <button type="submit" class="px-4 py-2 bg-sage-dark hover:bg-sage-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-md shadow-md active:scale-95 transition-all cursor-pointer">
                            Salva Dati
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white p-2.5 border border-[#D1B280]/40 rounded-sm shadow-xl relative w-full mx-auto">
        <div class="border border-[#D1B280]/60 p-6 sm:p-8 relative paper-texture">
            
            <!-- Tab Switcher (Only for Admin) -->
            @if(Auth::user()->isAdmin())
                <div class="flex border-b border-zinc-200 gap-6 mb-6 select-none">
                    <button type="button" wire:click="$set('activeTab', 'accounts')" 
                            class="pb-3 font-serif text-sm font-semibold border-b-2 px-1 transition-all cursor-pointer {{ $activeTab === 'accounts' ? 'border-gold-dark text-gold-dark' : 'border-transparent text-charcoal-light hover:text-charcoal' }}">
                        Dati Account ({{ count($this->records) }})
                    </button>
                    <button type="button" wire:click="$set('activeTab', 'rsvp')" 
                            class="pb-3 font-serif text-sm font-semibold border-b-2 px-1 transition-all cursor-pointer {{ $activeTab === 'rsvp' ? 'border-gold-dark text-gold-dark' : 'border-transparent text-charcoal-light hover:text-charcoal' }}">
                        Partecipazioni RSVP ({{ count($this->rsvps) }})
                    </button>
                    <button type="button" wire:click="$set('activeTab', 'map')" 
                            class="pb-3 font-serif text-sm font-semibold border-b-2 px-1 transition-all cursor-pointer {{ $activeTab === 'map' ? 'border-gold-dark text-gold-dark' : 'border-transparent text-charcoal-light hover:text-charcoal' }}">
                        Mappa Tavoli (Seating Plan)
                    </button>
                </div>
            @endif

            @if(!Auth::user()->isAdmin() || $activeTab === 'accounts')
                <h3 class="font-serif text-lg text-charcoal font-semibold tracking-wide mb-4">
                    {{ Auth::user()->isAdmin() ? 'Lista Completa Dati Personali (Tutti gli Ospiti)' : 'I tuoi Dati Personali Registrati' }}
                </h3>

                @if(count($this->records) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left font-serif border-collapse">
                            <thead>
                                <tr class="border-b border-[#D1B280]/30 text-[10px] uppercase tracking-wider text-charcoal-light">
                                    @if(Auth::user()->isAdmin())
                                        <th class="py-3 px-4 font-semibold">User</th>
                                    @endif
                                    <th class="py-3 px-4 font-semibold">Nome e Cognome</th>
                                    <th class="py-3 px-4 font-semibold">Telefono <span class="text-[9px] text-emerald-600 bg-emerald-50 px-1 py-0.5 rounded border border-emerald-100 font-normal">Cifrato</span></th>
                                    <th class="py-3 px-4 font-semibold">Posti</th>
                                    <th class="py-3 px-4 font-semibold">Note / Allergie <span class="text-[9px] text-emerald-600 bg-emerald-50 px-1 py-0.5 rounded border border-emerald-100 font-normal">Cifrato</span></th>
                                    <th class="py-3 px-4 font-semibold text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-charcoal">
                                @foreach($this->records as $rec)
                                    <tr class="border-b border-zinc-100 hover:bg-[#FAF6F0]/50 transition-colors">
                                        @if(Auth::user()->isAdmin())
                                            <td class="py-3.5 px-4 font-sans font-semibold text-xs text-zinc-500">
                                                {{ $rec['user']['username'] ?? 'N/A' }}
                                            </td>
                                        @endif
                                        <td class="py-3.5 px-4 font-semibold">
                                            {{ $rec['full_name'] }}
                                        </td>
                                        <td class="py-3.5 px-4 text-xs font-mono text-zinc-600">
                                            {{ $rec['phone'] ?: '-' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-xs font-semibold">
                                            {{ $rec['confirmed_seats'] }}
                                        </td>
                                        <td class="py-3.5 px-4 text-xs italic text-zinc-600 max-w-xs truncate">
                                            {{ $rec['notes'] ?: '-' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <button wire:click="edit({{ $rec['id'] }})" class="p-1.5 text-zinc-500 hover:text-gold-dark rounded-md hover:bg-zinc-100 transition-colors cursor-pointer" title="Modifica">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <button onclick="confirm('Confermi l\'eliminazione sicura di questi dati personali?') || event.stopImmediatePropagation()" wire:click="delete({{ $rec['id'] }})" class="p-1.5 text-zinc-500 hover:text-red-600 rounded-md hover:bg-zinc-100 transition-colors cursor-pointer" title="Elimina">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 bg-[#FAF6F0]/40 rounded border border-dashed border-[#D1B280]/20 font-serif">
                        <svg class="w-8 h-8 text-zinc-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <p class="text-sm text-charcoal-light">Nessuna conferma RSVP ancora ricevuta.</p>
                    </div>
                @endif
            @endif

            @if(Auth::user()->isAdmin() && $activeTab === 'map')
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#D1B280]/20 pb-3">
                        <h3 class="font-serif text-lg text-charcoal font-semibold tracking-wide">
                            Mappa Interattiva dei Tavoli - Planimetria Sala
                        </h3>
                        <div class="flex flex-wrap gap-2 self-start sm:self-auto">
                            <button type="button" wire:click="downloadExcel" 
                                    class="px-3.5 py-1.5 bg-sage-dark hover:bg-sage-medium text-white font-serif text-[10px] sm:text-xs uppercase tracking-wider font-semibold rounded shadow-md hover:shadow-lg active:scale-95 transition-all duration-200 cursor-pointer flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Excel
                            </button>
                            <button type="button" onclick="downloadPNGPlan()" 
                                    class="px-3.5 py-1.5 bg-white hover:bg-zinc-50 border border-[#D1B280] text-gold-dark font-serif text-[10px] sm:text-xs uppercase tracking-wider font-semibold rounded shadow-md hover:shadow-lg active:scale-95 transition-all duration-200 cursor-pointer flex items-center justify-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Scarica Planimetria (PNG)
                            </button>
                        </div>
                    </div>
                    
                    @php
                        $guestsData = $this->getGuestsData();
                        $allAttending = $guestsData['attending'];
                        $unassignedGuests = $guestsData['unassigned'];
                        $assignedGuests = $guestsData['assignedToSelected'];
                    @endphp

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Column 1 & 2: SVG Seating Chart Map -->
                        <div class="lg:col-span-2 flex justify-center">
                            <div class="w-full max-w-xl">
                                <svg id="seating-chart-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 760 810" class="w-full h-auto bg-white border border-zinc-200 rounded shadow-sm select-none">
                                    <!-- Background grid/pattern -->
                                    <rect width="100%" height="100%" fill="#FAF6F0" opacity="0.3" />

                                    <!-- Exterior walls (thick black lines) -->
                                    <line x1="80" y1="50" x2="680" y2="50" stroke="black" stroke-width="8" /> <!-- Top -->
                                    <line x1="80" y1="760" x2="680" y2="760" stroke="black" stroke-width="8" /> <!-- Bottom -->
                                    <line x1="80" y1="50" x2="80" y2="760" stroke="black" stroke-width="8" /> <!-- Left -->
                                    <line x1="680" y1="50" x2="680" y2="760" stroke="black" stroke-width="8" />

                                    <!-- Left decor wall projections -->
                                    <rect x="80" y="300" width="15" height="120" fill="black" opacity="0.9" />
                                    <rect x="665" y="300" width="15" height="120" fill="black" opacity="0.9" />

                                    <!-- Entrance ("ENTRATA") -->
                                    <g transform="translate(560, 760)">
                                        <path d="M 0,0 L 0,-25" stroke="black" stroke-width="2.5" />
                                        <path d="M -8,-15 L 0,-25 L 8,-15" fill="none" stroke="black" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <text x="0" y="-35" text-anchor="middle" font-family="Georgia, Cambria, 'Times New Roman', Times, serif" font-size="10" font-weight="bold" fill="#2D3130" letter-spacing="1">ENTRATA</text>
                                    </g>

                                    <!-- Music ("MUSICA") -->
                                    <g transform="translate(420, 755)">
                                        <rect x="-30" y="-12" width="60" height="15" fill="white" stroke="black" stroke-width="1.5" />
                                        <text x="0" y="-1" text-anchor="middle" font-family="Georgia, Cambria, 'Times New Roman', Times, serif" font-size="8.5" font-weight="bold" fill="#2D3130">MUSICA</text>
                                    </g>

                                    <!-- Stairs ("SCALA") -->
                                    <g transform="translate(300, 755)">
                                        <rect x="-30" y="-10" width="60" height="10" fill="white" stroke="black" stroke-width="1" />
                                        <line x1="-20" y1="-10" x2="-20" y2="0" stroke="black" stroke-width="0.8" />
                                        <line x1="-10" y1="-10" x2="-10" y2="0" stroke="black" stroke-width="0.8" />
                                        <line x1="0" y1="-10" x2="0" y2="0" stroke="black" stroke-width="0.8" />
                                        <line x1="10" y1="-10" x2="10" y2="0" stroke="black" stroke-width="0.8" />
                                        <line x1="20" y1="-10" x2="20" y2="0" stroke="black" stroke-width="0.8" />
                                        <text x="0" y="-14" text-anchor="middle" font-family="Georgia, Cambria, 'Times New Roman', Times, serif" font-size="7.5" fill="#71717A">SCALA</text>
                                    </g>

                                    <!-- Header texts -->
                                    <text x="220" y="32" text-anchor="middle" font-family="Georgia, Cambria, 'Times New Roman', Times, serif" font-size="11" font-weight="bold" fill="#2D3130" letter-spacing="1">OSPITI SPOSA</text>
                                    <text x="540" y="32" text-anchor="middle" font-family="Georgia, Cambria, 'Times New Roman', Times, serif" font-size="11" font-weight="bold" fill="#2D3130" letter-spacing="1">OSPITI SPOSO</text>

                                    <!-- Tables -->
                                    @foreach($tables as $table)
                                        @php
                                            $tableGuests = collect($allAttending)->where('table_number', $table['id']);
                                            $count = $tableGuests->count();
                                            
                                            // Check for intolerance / food allergy
                                            $hasIntolerances = $tableGuests->contains(function ($guest) {
                                                return !empty(trim($guest['allergies'] ?? '')) || !empty(trim($guest['dietary_requirements'] ?? ''));
                                            });

                                            $fillVal = '#FFFFFF';
                                            $strokeVal = '#D1B280';
                                            $strokeOpacity = 0.7;
                                            $strokeWidthVal = 1;
                                            
                                            if ($table['id'] === 'sposi') {
                                                $fillVal = '#FAF6F0';
                                                $strokeVal = '#D1B280';
                                                $strokeOpacity = 1.0;
                                                $strokeWidthVal = 1.5;
                                            }
                                            
                                            if ($hasIntolerances) {
                                                $fillVal = '#E8F5E9'; // Light emerald/green
                                                $strokeVal = '#10B981'; // Emerald border
                                                $strokeOpacity = 1.0;
                                                $strokeWidthVal = 2.0;
                                            } elseif ($count > 0 && $count < 8) {
                                                // Partially occupied (light sage green)
                                                $fillVal = '#F0F4ED';
                                                $strokeVal = '#8FA282';
                                                $strokeOpacity = 1.0;
                                            } elseif ($count >= 8) {
                                                // Highly occupied (darker sage green)
                                                $fillVal = '#D8E2D2';
                                                $strokeVal = '#5E6D4E';
                                                $strokeOpacity = 1.0;
                                                $strokeWidthVal = 1.5;
                                            }
                                            
                                            // Selected state
                                            if ($selectedTableId == $table['id']) {
                                                $strokeVal = '#F59E0B'; // amber-500
                                                $strokeWidthVal = 2.5;
                                                $strokeOpacity = 1.0;
                                            }
                                        @endphp

                                        <g class="cursor-pointer group" wire:click="$set('selectedTableId', '{{ $table['id'] }}')">
                                            <!-- Tooltip hint -->
                                            <title>Tavolo {{ $table['id'] === 'sposi' ? 'Sposi' : $table['id'] }} (Ospiti: {{ $count }}){{ $hasIntolerances ? ' - ATTENZIONE: Ospiti con intolleranze!' : '' }}</title>

                                            <!-- Main table circle -->
                                            <circle cx="{{ $table['x'] }}" cy="{{ $table['y'] }}" r="23" fill="{{ $fillVal }}" stroke="{{ $strokeVal }}" stroke-opacity="{{ $strokeOpacity }}" stroke-width="{{ $strokeWidthVal }}" class="transition-all duration-200 group-hover:stroke-amber-400" />
                                            
                                            <!-- Chairs around table -->
                                            @for($i = 0; $i < 10; $i++)
                                                @php
                                                    $angle = ($i * 36) * pi() / 180;
                                                    $chairX = $table['x'] + 30 * cos($angle);
                                                    $chairY = $table['y'] + 30 * sin($angle);
                                                    
                                                    $chairFillVal = '#FFFFFF';
                                                    $chairStrokeVal = '#D1B280';
                                                    $chairStrokeOpacity = 0.6;
                                                    
                                                    if ($i < $count) {
                                                        $chairFillVal = '#5E6D4E';
                                                        $chairStrokeVal = '#5E6D4E';
                                                        $chairStrokeOpacity = 1.0;
                                                    }
                                                @endphp
                                                <circle cx="{{ $chairX }}" cy="{{ $chairY }}" r="3.5" fill="{{ $chairFillVal }}" stroke="{{ $chairStrokeVal }}" stroke-opacity="{{ $chairStrokeOpacity }}" class="transition-all duration-150" />
                                            @endfor
                                            
                                            <!-- Text number -->
                                            <text x="{{ $table['x'] }}" y="{{ $table['y'] + 3.5 }}" text-anchor="middle" font-family="Georgia, Cambria, 'Times New Roman', Times, serif" font-size="9.5" font-weight="bold" fill="#2D3130" class="select-none group-hover:fill-gold-dark">
                                                {{ $table['id'] === 'sposi' ? 'Sposi' : $table['id'] }}
                                            </text>
                                        </g>
                                    @endforeach
                                </svg>
                            </div>
                        </div>

                        <!-- Column 3: Table Management & Seating Panel -->
                        <div class="space-y-4">
                            @if (!empty($selectedTableId))
                                @php
                                    // Calculate stats for selected table
                                    $adults = 0;
                                    $babyMenus = 0;
                                    $highchairs = 0;
                                    $chairs = 0;
                                    
                                    foreach ($assignedGuests as $guest) {
                                        if ($guest['is_child']) {
                                            if ($guest['needs_baby_menu']) {
                                                $babyMenus++;
                                            } else {
                                                $adults++; // counts as adult menu
                                            }
                                            
                                            if ($guest['needs_highchair']) {
                                                $highchairs++;
                                            } else {
                                                $chairs++;
                                            }
                                        } else {
                                            $adults++;
                                            $chairs++;
                                        }
                                    }
                                @endphp
                                
                                <!-- Seating details card -->
                                <div class="bg-white border border-[#D1B280]/40 p-4 rounded shadow-sm space-y-4 animate-fadeIn">
                                    <div class="border-b border-[#D1B280]/20 pb-2 flex justify-between items-center">
                                        <h4 class="font-serif text-sm font-semibold text-gold-dark uppercase tracking-wider">
                                            Tavolo {{ $selectedTableId === 'sposi' ? 'Sposi' : $selectedTableId }}
                                        </h4>
                                        <span class="text-[10px] text-zinc-400 font-sans font-light">Assegnati: {{ count($assignedGuests) }} / 10</span>
                                    </div>

                                    <!-- Statistics Badge Row -->
                                    <div class="grid grid-cols-2 gap-2 text-center text-[10px] font-serif">
                                        <div class="bg-[#FAF6F0] p-1.5 rounded border border-[#D1B280]/15">
                                            <span class="text-zinc-500 block">Menu Adulti (AD)</span>
                                            <span class="font-bold text-sm text-charcoal">{{ $adults }}</span>
                                        </div>
                                        <div class="bg-[#FAF6F0] p-1.5 rounded border border-[#D1B280]/15">
                                            <span class="text-zinc-500 block">Menu Baby (MB)</span>
                                            <span class="font-bold text-sm text-charcoal">{{ $babyMenus }}</span>
                                        </div>
                                        <div class="bg-[#FAF6F0] p-1.5 rounded border border-[#D1B280]/15">
                                            <span class="text-zinc-500 block">Sedie (Regular)</span>
                                            <span class="font-bold text-sm text-charcoal">{{ $chairs }}</span>
                                        </div>
                                        <div class="bg-[#FAF6F0] p-1.5 rounded border border-[#D1B280]/15">
                                            <span class="text-zinc-500 block">Seggioloni</span>
                                            <span class="font-bold text-sm text-charcoal">{{ $highchairs }}</span>
                                        </div>
                                    </div>

                                    <!-- Table guests list -->
                                    <div class="space-y-2">
                                        <span class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold">Ospiti Seduti:</span>
                                        
                                        @if (count($assignedGuests) > 0)
                                            <div class="space-y-1.5 max-h-56 overflow-y-auto pr-1">
                                                @foreach($assignedGuests as $g)
                                                    <div class="flex justify-between items-center p-2 rounded bg-zinc-50 border border-zinc-150 text-xs font-serif">
                                                        <div class="flex flex-col">
                                                            <span class="font-semibold text-charcoal">{{ $g['name'] }}</span>
                                                            @if($g['is_pregnant'] || !empty($g['allergies']) || ($g['is_child'] && ($g['needs_highchair'] || $g['needs_baby_menu'])))
                                                                <span class="text-[9px] text-zinc-500 font-sans mt-0.5 flex flex-wrap gap-1">
                                                                    @if($g['is_pregnant']) <span class="bg-pink-50 text-pink-700 px-1 rounded">🤰 Gravidanza</span> @endif
                                                                    @if($g['is_child'] && $g['needs_highchair']) <span class="bg-amber-50 text-amber-700 px-1 rounded">👶 Seggiolone</span> @endif
                                                                    @if($g['is_child'] && $g['needs_baby_menu']) <span class="bg-blue-50 text-blue-700 px-1 rounded">🍼 Menu Baby</span> @endif
                                                                    @if(!empty($g['allergies'])) <span class="bg-red-50 text-red-700 px-1 rounded max-w-[100px] truncate" title="Allergie: {{ $g['allergies'] }}">🥗 Allergie: {{ $g['allergies'] }}</span> @endif
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <button type="button" wire:click="removeFromTable('{{ $g['type'] }}', {{ $g['id'] }})" class="text-zinc-400 hover:text-red-500 p-1 rounded-md transition-colors cursor-pointer" title="Rimuovi dal tavolo">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-xs italic text-zinc-400 text-center py-4 border border-dashed border-zinc-200 rounded">Nessun ospite ancora assegnato a questo tavolo.</p>
                                        @endif
                                    </div>

                                    <!-- Add guest form block -->
                                    <div class="border-t border-[#D1B280]/20 pt-3 space-y-2.5">
                                        <span class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold">Aggiungi Ospite al tavolo:</span>
                                        
                                        @if (count($unassignedGuests) > 0)
                                            <form wire:submit.prevent="assignToTable('{{ $selectedTableId }}')" class="flex gap-2">
                                                <select wire:model="assigneeGuestId" class="flex-1 px-2.5 py-1.5 bg-zinc-50 border border-zinc-200 rounded text-xs text-charcoal" required>
                                                    <option value="">Seleziona invitato...</option>
                                                    @foreach($unassignedGuests as $un)
                                                        <option value="{{ $un['type'] }}-{{ $un['id'] }}">{{ $un['name'] }}</option>
                                                    @endforeach
                                                </select>

                                                <button type="submit" class="px-3 py-1.5 bg-sage-dark hover:bg-sage-medium text-white font-serif text-[10px] uppercase tracking-widest font-semibold rounded shadow active:scale-95 transition-all cursor-pointer">
                                                    Siedi
                                                </button>
                                            </form>
                                        @else
                                            <p class="text-[10px] text-zinc-400 font-serif italic">Tutti gli ospiti confermati sono già stati assegnati a un tavolo.</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- No table selected hint -->
                                <div class="bg-white border border-[#D1B280]/30 p-8 rounded shadow-sm text-center font-serif text-charcoal-light py-20 flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-zinc-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                    <p class="text-xs leading-relaxed max-w-[200px]">Clicca su un tavolo della mappa planimetrica per visualizzarne i dettagli e sedere gli ospiti.</p>
                                </div>
                            @endif

                            <!-- Unassigned guests overview card -->
                            <div class="bg-white border border-[#D1B280]/40 p-4 rounded shadow-sm space-y-3">
                                <div class="border-b border-[#D1B280]/20 pb-1.5 flex justify-between items-center">
                                    <h5 class="font-serif text-xs font-semibold text-charcoal uppercase tracking-wider">Ospiti Non Assegnati</h5>
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded text-[9px] font-semibold font-serif">{{ count($unassignedGuests) }}</span>
                                </div>
                                
                                @if (count($unassignedGuests) > 0)
                                    <div class="max-h-48 overflow-y-auto space-y-1 pr-1">
                                        @foreach($unassignedGuests as $un)
                                            <div class="p-1.5 bg-zinc-50 border border-zinc-150 rounded text-[11px] font-serif text-charcoal-light flex justify-between items-center">
                                                <span>{{ $un['name'] }}</span>
                                                @if(!empty($selectedTableId))
                                                    <button type="button" wire:click="assignToTable('{{ $un['type'] }}', {{ $un['id'] }}, '{{ $selectedTableId }}')" class="text-sage-dark hover:text-sage-medium font-bold text-[9px] uppercase tracking-wider cursor-pointer" title="Siedi al tavolo selezionato">
                                                        Siedi qui
                                                    </button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-[10px] italic text-zinc-400 text-center py-2">Tutti gli ospiti sono seduti!</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Guest Table Card -->
                    <div class="bg-white border border-[#D1B280]/40 rounded-sm shadow-md mt-6 p-6 sm:p-8 relative paper-texture">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                            <div>
                                <h4 class="font-serif text-base text-charcoal font-semibold tracking-wide">
                                    Modifica Dettagli Invitati e Tavoli
                                </h4>
                                <p class="text-[10px] uppercase tracking-wider text-charcoal-light font-semibold mt-0.5">
                                    Gestisci tutti i dati inseriti nel form di partecipazione (RSVP)
                                </p>
                            </div>
                            <!-- Actions (Search & Export) -->
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                                <div class="w-full sm:w-64">
                                    <input type="text" wire:model.live.debounce.300ms="guestSearch" 
                                           placeholder="Cerca invitato per nome..."
                                           class="w-full px-3 py-1.5 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md text-xs text-charcoal focus:outline-none focus:ring-1 focus:ring-sage-medium">
                                </div>
                                <button type="button" wire:click="exportGuestsCsv" 
                                        class="px-3.5 py-1.5 bg-sage-dark hover:bg-sage-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded shadow-md hover:shadow-lg active:scale-95 transition-all duration-200 cursor-pointer flex items-center justify-center gap-1.5 self-stretch sm:self-auto">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Esporta Excel
                                </button>
                            </div>
                        </div>

                        @php
                            $allGuestsList = $this->getAllGuestsForTable();
                        @endphp

                        @if(count($allGuestsList) > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left font-serif border-collapse text-xs">
                                    <thead>
                                        <tr class="border-b border-[#D1B280]/30 text-[9px] uppercase tracking-wider text-charcoal-light">
                                            <th class="py-2.5 px-3 font-semibold">Nominativo</th>
                                            <th class="py-2.5 px-3 font-semibold">Ruolo</th>
                                            <th class="py-2.5 px-3 font-semibold">Tavolo</th>
                                            <th class="py-2.5 px-3 font-semibold">Presenza</th>
                                            <th class="py-2.5 px-3 font-semibold">Gravidanza</th>
                                            <th class="py-2.5 px-3 font-semibold">Allergie</th>
                                            <th class="py-2.5 px-3 font-semibold">Scelte Alim.</th>
                                            <th class="py-2.5 px-3 font-semibold">Dettagli Bimbo</th>
                                            <th class="py-2.5 px-3 font-semibold text-center">Regalo</th>
                                            <th class="py-2.5 px-3 font-semibold text-center">Bomboniera</th>
                                            <th class="py-2.5 px-3 font-semibold">Note</th>
                                            <th class="py-2.5 px-3 font-semibold text-right">Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-[13px] text-charcoal">
                                        @foreach($allGuestsList as $g)
                                            @php
                                                $isRowEditing = ($editingGuestId == $g['id'] && $editingGuestType == $g['type']);
                                            @endphp
                                            <tr class="border-b border-zinc-100 hover:bg-[#FAF6F0]/30 transition-colors {{ $isRowEditing ? 'bg-amber-50/30' : '' }}">
                                                
                                                <!-- Nominativo (Nome + Cognome) -->
                                                <td class="py-3 px-3">
                                                    @if($isRowEditing)
                                                        <div class="flex gap-1.5">
                                                            <input type="text" wire:model="editFirstName" class="w-20 px-2 py-1 bg-white border border-zinc-200 rounded text-xs" placeholder="Nome">
                                                            <input type="text" wire:model="editLastName" class="w-24 px-2 py-1 bg-white border border-zinc-200 rounded text-xs" placeholder="Cognome">
                                                        </div>
                                                        @error('editFirstName') <span class="text-red-500 text-[9px] block">{{ $message }}</span> @enderror
                                                        @error('editLastName') <span class="text-red-500 text-[9px] block">{{ $message }}</span> @enderror
                                                    @else
                                                        <span class="font-semibold">{{ $g['first_name'] }} {{ $g['last_name'] }}</span>
                                                        @if(!empty($g['notes']) && preg_match('/Inserito automaticamente dalle note di (.+)/', $g['notes'], $matches))
                                                            <span class="text-[10px] text-sage-dark font-sans block mt-0.5 italic font-normal">👤 Collegato a: {{ $matches[1] }}</span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <!-- Ruolo -->
                                                <td class="py-3 px-3">
                                                    @if($isRowEditing && $g['type'] === 'member')
                                                        <select wire:model.live="editMemberType" class="px-2 py-1 bg-white border border-zinc-200 rounded text-xs">
                                                            <option value="spouse">Coniuge/Partner</option>
                                                            <option value="companion">Accompagnatore</option>
                                                            <option value="child">Figlio/a</option>
                                                        </select>
                                                    @else
                                                        @if($g['member_type'] === 'primary')
                                                            <span class="px-1.5 py-0.5 text-[8.5px] uppercase tracking-wider font-semibold rounded bg-blue-50 text-blue-700 border border-blue-200">Capogruppo</span>
                                                        @elseif($g['member_type'] === 'spouse')
                                                            <span class="px-1.5 py-0.5 text-[8.5px] uppercase tracking-wider font-semibold rounded bg-purple-50 text-purple-700 border border-purple-200">Partner</span>
                                                        @elseif($g['member_type'] === 'companion')
                                                            <span class="px-1.5 py-0.5 text-[8.5px] uppercase tracking-wider font-semibold rounded bg-zinc-100 text-zinc-650 border border-zinc-200">Accomp.</span>
                                                        @elseif($g['member_type'] === 'child')
                                                            <span class="px-1.5 py-0.5 text-[8.5px] uppercase tracking-wider font-semibold rounded bg-amber-50 text-amber-700 border border-amber-250">Figlio/a</span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <!-- Tavolo -->
                                                <td class="py-3 px-3 font-semibold">
                                                    @if($isRowEditing)
                                                        <select wire:model="editTableNumber" class="px-2 py-1 bg-white border border-zinc-200 rounded text-xs w-20">
                                                            <option value="">Non ass.</option>
                                                            <option value="sposi">Sposi</option>
                                                            @for($i = 1; $i <= 35; $i++)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    @else
                                                        @if(empty($g['table_number']))
                                                            <span class="text-zinc-400 font-normal italic text-xs">Non ass.</span>
                                                        @else
                                                            <span class="text-gold-dark">Tavolo {{ $g['table_number'] === 'sposi' ? 'Sposi' : $g['table_number'] }}</span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <!-- Presenza -->
                                                <td class="py-3 px-3">
                                                    @if($isRowEditing)
                                                        <select wire:model="editWillAttend" class="px-2 py-1 bg-white border border-zinc-200 rounded text-xs">
                                                            <option value="1">Sì</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    @else
                                                        @if($g['will_attend'])
                                                            <span class="text-emerald-700 font-semibold text-xs flex items-center gap-0.5">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Presente
                                                            </span>
                                                        @else
                                                            <span class="text-zinc-400 italic text-xs flex items-center gap-0.5">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-zinc-300"></span> Assente
                                                            </span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <!-- Gravidanza -->
                                                <td class="py-3 px-3 text-center">
                                                    @if($isRowEditing)
                                                        <input type="checkbox" wire:model="editIsPregnant" class="w-3.5 h-3.5 rounded text-sage-medium focus:ring-sage-light">
                                                    @else
                                                        @if($g['is_pregnant'])
                                                            <span class="text-pink-600 text-xs" title="In dolce attesa">🤰 Sì</span>
                                                        @else
                                                            <span class="text-zinc-400 text-xs">-</span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <!-- Allergie -->
                                                <td class="py-3 px-3 min-w-[150px] break-words">
                                                    @if($isRowEditing)
                                                        <input type="text" wire:model="editAllergies" class="w-full px-2 py-1 bg-white border border-zinc-200 rounded text-xs" placeholder="Allergie">
                                                    @else
                                                        <span class="italic text-zinc-600">{{ $g['allergies'] ?: '-' }}</span>
                                                    @endif
                                                </td>

                                                <!-- Scelte Alimentari -->
                                                <td class="py-3 px-3 max-w-[120px] truncate">
                                                    @if($isRowEditing)
                                                        <input type="text" wire:model="editDietaryRequirements" class="w-full px-2 py-1 bg-white border border-zinc-200 rounded text-xs" placeholder="Dieta">
                                                    @else
                                                        <span class="italic text-zinc-600" title="{{ $g['dietary_requirements'] ?: '' }}">{{ $g['dietary_requirements'] ?: '-' }}</span>
                                                    @endif
                                                </td>

                                                <!-- Dettagli Bimbo -->
                                                <td class="py-3 px-3">
                                                    @if($isRowEditing)
                                                        @if(($g['type'] === 'member' && $editMemberType === 'child') || ($g['type'] === 'primary' && $g['member_type'] === 'child'))
                                                            <div class="space-y-1.5 p-1 bg-zinc-50 border border-zinc-200 rounded">
                                                                <div class="flex items-center gap-1">
                                                                    <span class="text-[9px] text-zinc-500">Età:</span>
                                                                    <input type="number" wire:model="editAge" class="w-12 px-1.5 py-0.5 bg-white border border-zinc-200 rounded text-[11px]" min="0" max="17">
                                                                </div>
                                                                <div class="flex items-center gap-1">
                                                                    <input type="checkbox" id="editHighchair-{{ $g['type'] }}-{{ $g['id'] }}" wire:model="editNeedsHighchair" class="w-3 h-3 text-sage-medium">
                                                                    <label for="editHighchair-{{ $g['type'] }}-{{ $g['id'] }}" class="text-[9px] text-zinc-500">Seggiolone</label>
                                                                </div>
                                                                <div class="flex items-center gap-1">
                                                                    <input type="checkbox" id="editBabyMenu-{{ $g['type'] }}-{{ $g['id'] }}" wire:model="editNeedsBabyMenu" class="w-3 h-3 text-sage-medium">
                                                                    <label for="editBabyMenu-{{ $g['type'] }}-{{ $g['id'] }}" class="text-[9px] text-zinc-500">Menu Bimbo</label>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-zinc-400 font-light italic text-[11px]">-</span>
                                                        @endif
                                                    @else
                                                        @if($g['member_type'] === 'child')
                                                            <div class="text-[10px] space-y-0.5 font-sans">
                                                                <div class="text-zinc-650">Età: <span class="font-bold">{{ $g['age'] }}</span></div>
                                                                @if($g['needs_highchair']) <span class="bg-amber-50 text-amber-700 px-1 py-0.5 rounded border border-amber-100 text-[8px] font-semibold">Seggiolone</span> @endif
                                                                @if($g['needs_baby_menu']) <span class="bg-blue-50 text-blue-700 px-1 py-0.5 rounded border border-blue-100 text-[8px] font-semibold">Menu Baby</span> @endif
                                                            </div>
                                                        @else
                                                            <span class="text-zinc-400 text-xs">-</span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <!-- Regalo -->
                                                <td class="py-3 px-3 text-center">
                                                    @if($isRowEditing)
                                                        <input type="checkbox" id="editHasGift-{{ $g['type'] }}-{{ $g['id'] }}" wire:model="editHasGift" class="w-3.5 h-3.5 rounded text-sage-medium focus:ring-sage-light">
                                                    @else
                                                        @if($g['has_gift'])
                                                            <span class="text-emerald-700 text-xs font-semibold" title="Regalo ricevuto">🎁 Sì</span>
                                                        @else
                                                            <span class="text-zinc-400 text-xs" title="Nessun regalo">No</span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <!-- Bomboniera -->
                                                <td class="py-3 px-3 text-center">
                                                    @if($isRowEditing)
                                                        <input type="checkbox" id="editReceivesFavor-{{ $g['type'] }}-{{ $g['id'] }}" wire:model="editReceivesFavor" class="w-3.5 h-3.5 rounded text-sage-medium focus:ring-sage-light">
                                                    @else
                                                        @if($g['receives_favor'])
                                                            <span class="text-amber-700 text-xs font-semibold" title="Deve ricevere bomboniera">📦 Sì</span>
                                                        @else
                                                            <span class="text-zinc-400 text-xs" title="No bomboniera">No</span>
                                                        @endif
                                                    @endif
                                                </td>

                                                <!-- Note -->
                                                <td class="py-3 px-3 min-w-[150px] break-words">
                                                    @if($isRowEditing)
                                                        <input type="text" wire:model="editNotes" class="w-full px-2 py-1 bg-white border border-zinc-200 rounded text-xs" placeholder="Note">
                                                    @else
                                                        <span class="italic text-zinc-600">{{ $g['notes'] ?: '-' }}</span>
                                                    @endif
                                                </td>

                                                <!-- Azioni -->
                                                <td class="py-3 px-3 text-right">
                                                    @if($isRowEditing)
                                                        <div class="flex justify-end gap-1">
                                                            <button type="button" wire:click="saveGuest" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[10px] font-semibold tracking-wider uppercase shadow active:scale-95 transition-all cursor-pointer">
                                                                Salva
                                                            </button>
                                                            <button type="button" wire:click="cancelEditGuest" class="px-2 py-1 bg-zinc-200 hover:bg-zinc-300 text-zinc-700 rounded text-[10px] font-semibold tracking-wider uppercase active:scale-95 transition-all cursor-pointer">
                                                                Annulla
                                                            </button>
                                                        </div>
                                                    @else
                                                        <button type="button" wire:click="startEditGuest('{{ $g['type'] }}', {{ $g['id'] }})" class="px-2.5 py-1 border border-gold-medium/50 text-gold-dark hover:border-gold-dark hover:bg-gold-light/10 rounded text-[10px] font-bold tracking-wider uppercase transition-colors cursor-pointer">
                                                            Modifica
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-10 border border-dashed border-zinc-200 rounded text-zinc-400 font-serif">
                                Nessun invitato trovato con i criteri di ricerca.
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if(Auth::user()->isAdmin() && $activeTab === 'rsvp')
                <h3 class="font-serif text-lg text-charcoal font-semibold tracking-wide mb-4">
                    Lista Conferme RSVP Ricevute
                </h3>

                @if(count($this->rsvps) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left font-serif border-collapse">
                            <thead>
                                <tr class="border-b border-[#D1B280]/30 text-[10px] uppercase tracking-wider text-charcoal-light">
                                    <th class="py-3 px-4 font-semibold">Ospite</th>
                                    <th class="py-3 px-4 font-semibold">Tipo</th>
                                    <th class="py-3 px-4 font-semibold">Presenza</th>
                                    <th class="py-3 px-4 font-semibold">Gravidanza</th>
                                    <th class="py-3 px-4 font-semibold">Coperti (Pres/Tot)</th>
                                    <th class="py-3 px-4 font-semibold">Allergie <span class="text-[9px] text-emerald-600 bg-emerald-50 px-1 py-0.5 rounded border border-emerald-100 font-normal">Cifrato</span></th>
                                    <th class="py-3 px-4 font-semibold">Altre Note <span class="text-[9px] text-emerald-600 bg-emerald-50 px-1 py-0.5 rounded border border-emerald-100 font-normal">Cifrato</span></th>
                                    <th class="py-3 px-4 font-semibold">Data Invio</th>
                                    <th class="py-3 px-4 font-semibold text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-charcoal">
                                @foreach($this->rsvps as $rsvp)
                                    <!-- Row Primary Guest -->
                                    <tr class="border-b border-zinc-100 hover:bg-[#FAF6F0]/50 transition-colors">
                                        <td class="py-3.5 px-4 font-semibold flex items-center gap-2 select-none">
                                            @if (count($rsvp['members']) > 0)
                                                <button type="button" wire:click="toggleRsvpExpansion({{ $rsvp['id'] }})" class="p-1 hover:bg-zinc-100 rounded text-zinc-500 hover:text-gold-dark transition-all cursor-pointer">
                                                    <svg class="w-4 h-4 transform transition-transform {{ $expandedRsvpId === $rsvp['id'] ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </button>
                                            @else
                                                <span class="w-6"></span>
                                            @endif
                                            <span>{{ $rsvp['first_name'] }} {{ $rsvp['last_name'] }}</span>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if ($rsvp['rsvp_type'] === 'family')
                                                <span class="px-2 py-0.5 text-[9px] uppercase tracking-wider font-semibold rounded bg-amber-50 text-amber-700 border border-amber-200">Famiglia</span>
                                            @else
                                                <span class="px-2 py-0.5 text-[9px] uppercase tracking-wider font-semibold rounded bg-blue-50 text-blue-700 border border-blue-200">Singolo</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if($rsvp['will_attend'])
                                                <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Sì, Presente</span>
                                            @else
                                                <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-semibold rounded-full bg-zinc-100 text-zinc-500 border border-zinc-200">No, Assente</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4">
                                            @if($rsvp['is_pregnant'])
                                                <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-semibold rounded-full bg-pink-50 text-pink-700 border border-pink-200">Gravidanza</span>
                                            @else
                                                <span class="text-zinc-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-xs font-semibold">
                                            @php
                                                $attendingMembersCount = collect($rsvp['members'])->where('will_attend', true)->count();
                                                $totalAttending = ($rsvp['will_attend'] ? 1 : 0) + $attendingMembersCount;
                                                $totalGroup = 1 + count($rsvp['members']);
                                            @endphp
                                            <span class="text-charcoal">{{ $totalAttending }}</span> <span class="text-zinc-400 font-normal">/ {{ $totalGroup }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs italic text-zinc-600 min-w-[150px] break-words">
                                            {{ $rsvp['allergies'] ?: '-' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-xs italic text-zinc-600 min-w-[150px] break-words">
                                            {{ $rsvp['notes'] ?: '-' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-zinc-500 font-sans">
                                            {{ date('d/m H:i', strtotime($rsvp['created_at'])) }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('stampa-invito', ['nome' => $rsvp['first_name'] . ' ' . $rsvp['last_name']]) }}" target="_blank" class="p-1.5 text-zinc-500 hover:text-gold-dark rounded-md hover:bg-zinc-100 transition-colors cursor-pointer" title="Stampa Invito per questo ospite">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 022 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                </a>
                                                <button onclick="confirm('Confermi l\'eliminazione sicura di questa partecipazione RSVP?') || event.stopImmediatePropagation()" wire:click="deleteRsvp({{ $rsvp['id'] }})" class="p-1.5 text-zinc-500 hover:text-red-600 rounded-md hover:bg-zinc-100 transition-colors cursor-pointer" title="Elimina">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Row Expanded Group Members -->
                                    @if ($expandedRsvpId === $rsvp['id'] && count($rsvp['members']) > 0)
                                        <tr class="bg-zinc-50/50">
                                            <td colspan="9" class="p-4 border-b border-zinc-100">
                                                <div class="bg-white p-4 border border-[#D1B280]/30 rounded-sm shadow-inner space-y-4">
                                                    <h4 class="font-serif text-[11px] font-bold text-gold-dark uppercase tracking-wider border-b border-[#D1B280]/20 pb-1.5 flex items-center justify-between">
                                                        <span>Dettaglio Famiglia / Accompagnatori ({{ count($rsvp['members']) }} membri)</span>
                                                        <span class="text-[9px] text-zinc-400 font-sans font-light normal-case">Dati crittografati decifrati a riposo</span>
                                                    </h4>

                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                        @foreach($rsvp['members'] as $member)
                                                            <div class="p-3 border border-zinc-100 bg-[#FAF6F0]/20 rounded space-y-2.5">
                                                                <div class="flex justify-between items-center border-b border-zinc-100 pb-1">
                                                                    <div class="flex flex-col">
                                                                        <span class="font-serif text-xs font-semibold text-charcoal">{{ $member['first_name'] }} {{ $member['last_name'] }}</span>
                                                                        <span class="text-[9px] font-sans uppercase text-zinc-400 font-semibold mt-0.5">
                                                                            @if($member['member_type'] === 'spouse') Partner / Coniuge
                                                                            @elseif($member['member_type'] === 'companion') Accompagnatore
                                                                            @elseif($member['member_type'] === 'child') Figlio/a (Età: {{ $member['age'] }} anni)
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex items-center gap-1.5">
                                                                        @if($member['will_attend'])
                                                                            <span class="px-2 py-0.5 text-[9px] font-serif font-semibold rounded bg-emerald-50 text-emerald-700 border border-emerald-200">Presente</span>
                                                                        @else
                                                                            <span class="px-2 py-0.5 text-[9px] font-serif font-semibold rounded bg-zinc-100 text-zinc-500">Assente</span>
                                                                        @endif
                                                                        <a href="{{ route('stampa-invito', ['nome' => $member['first_name'] . ' ' . $member['last_name']]) }}" target="_blank" class="p-1 text-zinc-500 hover:text-gold-dark rounded-md hover:bg-zinc-100 transition-colors cursor-pointer" title="Stampa Invito per questo ospite">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 022 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                                <div class="text-[11px] space-y-1 font-serif text-charcoal">
                                                                    @if($member['member_type'] === 'child' && $member['will_attend'])
                                                                        <div class="flex flex-wrap gap-1.5 py-0.5 select-none">
                                                                            @if($member['needs_highchair'])
                                                                                <span class="px-1.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-100 rounded text-[9px] font-semibold">👶 Seggiolino</span>
                                                                            @else
                                                                                <span class="px-1.5 py-0.5 bg-zinc-100 text-zinc-600 rounded text-[9px] font-semibold">🪑 Sedia Regular</span>
                                                                            @endif

                                                                            @if($member['needs_baby_menu'])
                                                                                <span class="px-1.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded text-[9px] font-semibold">🍼 Menu Baby</span>
                                                                            @else
                                                                                <span class="px-1.5 py-0.5 bg-zinc-100 text-zinc-600 rounded text-[9px] font-semibold">🍽️ Menu Adulto</span>
                                                                            @endif
                                                                        </div>
                                                                    @endif

                                                                    @if($member['is_pregnant'])
                                                                        <p><span class="text-zinc-400">Gravidanza:</span> <span class="px-1.5 py-0.5 bg-pink-50 text-pink-700 rounded text-[9px] font-semibold">Sì</span></p>
                                                                    @endif
                                                                    
                                                                    <p><span class="text-zinc-400 font-normal">Allergie/Intolleranze:</span> <span class="font-medium text-zinc-700">{{ $member['allergies'] ?: '-' }}</span></p>
                                                                    <p><span class="text-zinc-400 font-normal">Scelte alimentari:</span> <span class="font-medium text-zinc-700">{{ $member['dietary_requirements'] ?: '-' }}</span></p>
                                                                    <p><span class="text-zinc-400 font-normal">Altre Note:</span> <span class="italic text-zinc-600">{{ $member['notes'] ?: '-' }}</span></p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 bg-[#FAF6F0]/40 rounded border border-dashed border-[#D1B280]/20 font-serif">
                        <svg class="w-8 h-8 text-zinc-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <p class="text-sm text-charcoal-light">Nessuna conferma RSVP ancora ricevuta.</p>
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>

<script>
    window.downloadPNGPlan = function() {
        const svgElement = document.getElementById('seating-chart-svg');
        if (!svgElement) return;

        // Serialize the SVG to string
        const serializer = new XMLSerializer();
        let svgString = serializer.serializeToString(svgElement);

        // Strip Laravel Livewire custom attributes to prevent XML namespace errors in SVG drawing
        svgString = svgString.replace(/\swire:[a-zA-Z0-9.-]+=(['"])(.*?)\1/g, '');

        // Ensure namespaces are present
        if (!svgString.includes('xmlns="http://www.w3.org/2000/svg"')) {
            svgString = svgString.replace(/^<svg/, '<svg xmlns="http://www.w3.org/2000/svg"');
        }
        if (!svgString.includes('xmlns:xlink="http://www.w3.org/1999/xlink"')) {
            svgString = svgString.replace(/^<svg/, '<svg xmlns:xlink="http://www.w3.org/1999/xlink"');
        }

        // Add XML declaration
        svgString = '<' + '?xml version="1.0" standalone="no"?>\r\n' + svgString;

        // Convert the UTF-8 XML string to base64 safely
        const base64Svg = btoa(unescape(encodeURIComponent(svgString)));
        const imgSrc = 'data:image/svg+xml;base64,' + base64Svg;
        
        const image = new Image();
        image.onload = () => {
            try {
                const canvas = document.createElement('canvas');
                // High-resolution PNG (2x size of viewBox 760x810)
                canvas.width = 1520;
                canvas.height = 1620;
                const context = canvas.getContext('2d');
                
                // Solid white background
                context.fillStyle = '#ffffff';
                context.fillRect(0, 0, canvas.width, canvas.height);
                
                context.drawImage(image, 0, 0, canvas.width, canvas.height);
                
                // Use canvas.toBlob for robust same-origin downloads instead of top-level data: URIs
                canvas.toBlob((blob) => {
                    if (!blob) {
                        console.error("Failed to generate blob from canvas");
                        return;
                    }
                    const url = URL.createObjectURL(blob);
                    const downloadLink = document.createElement('a');
                    downloadLink.href = url;
                    downloadLink.download = 'Planimetria_Tavoli.png';
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                    
                    // Revoke object URL after a short timeout to let the browser initiate download
                    setTimeout(() => {
                        URL.revokeObjectURL(url);
                    }, 150);
                }, 'image/png');
            } catch (e) {
                console.error("Canvas export failed:", e);
                alert("Errore durante la generazione dell'immagine PNG: " + e.message);
            }
        };
        image.onerror = (err) => {
            console.error("Image loading failed:", err);
            alert("Impossibile caricare il disegno per la conversione in PNG. Controllare la console del browser.");
        };
        image.src = imgSrc;
    };
</script>
