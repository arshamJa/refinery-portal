<?php

namespace App\Livewire\admin;

use App\Models\Organization;
use App\Rules\farsi_chs;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class OrganizationTable extends Component
{
    use WithPagination,WithFileUploads, WithoutUrlPagination;

    public string $organization = '';
    public string $url = '';
    public $image;
    public ?string $search = '';
    public $organizationId = null;

    public function filterOrganizations()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.organization-table');
    }

    #[Computed]
    public function organizations()
    {
        return Organization::where('organization_name', 'like', "%{$this->search}%")
            ->orWhere('url', 'like', "%{$this->search}%")
            ->select('organization_name', 'id', 'url')
            ->paginate(5);
    }

    public function openModalCreate()
    {
        $this->dispatch('crud-modal',name:'create');
    }
    public function openImportModal()
    {
        $this->dispatch('crud-modal',name:'import');
    }

    public function openModalEdit($id)
    {
        $organizationName = Organization::findOrFail($id);
        $this->organization = $organizationName->organization_name;
        $this->url = $organizationName->url;
        $this->organizationId = $id;
        $this->dispatch('crud-modal',name:'update');
    }

    /**
     * @throws AuthorizationException
     */
    public function createOrg()
    {
        $validated = $this->validate([
            'organization' => ['bail','required','max:250'],
            'url' => ['bail','required','starts_with:www.'],
            'image' => ['nullable','mimes:jpg,jpeg,png,webp','max:1024']
        ]);
        $imagePath = null; // Initialize imagePath to null
        if ($this->image) { // Check if an image was uploaded
            $imagePath = $validated['image']->store('organizations', 'public');
        }
        Organization::create([
            'organization_name' => $validated['organization'],
            'url' => $validated['url'],
            'image' => $imagePath
        ]);
        $this->close();
        session()->flash('status', 'سامانه جدید با موفقیت ثبت شد');
    }

    /**
     * @throws AuthorizationException
     */
    public function updateOrg($organizationId)
    {
        $organization = Organization::findOrFail($organizationId);
        $validated = $this->validate([
            'organization' => ['bail','required','max:250'],
            'url' => ['bail','required','starts_with:www.'],
            'image' => ['nullable','mimes:jpg,jpeg,png,webp','max:1024']
        ]);
        // Handle Image Upload
        if ($validated['image']) { // If a new image is uploaded
            // Delete old image only if it exists
            if (!empty($organization->image)) {
                $oldImagePath = public_path('storage/' . $organization->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // Upload new image
            $validated['image'] = $validated['image']->store('organizations', 'public');
        }
        // Update organization
        $organization->update($validated);

        // Reset and close modal
        $this->reset(['organization', 'url', 'image']);
        $this->close();
        session()->flash('status', 'سامانه با موفقیت آپدیت شد');
    }
    public function openModalDelete($id)
    {
        $this->organization = Organization::where('id',$id)->value('organization_name');
        $this->organizationId = $id;
        $this->dispatch('crud-modal',name:'delete');
    }
    public function close()
    {
        $this->organizationId = '';
        $this->dispatch('close-modal');
        $this->redirectRoute('organizations');
    }


}
