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

    public string $organization;
    public string $url;
    public $image;
    public $newImage;

    public ?string $search = '';

    #[Locked]
    public $organizationId = '';

    public function render()
    {
        return view('livewire.admin.organization-table');
    }

    #[Computed]
    public function organizations()
    {
        return Organization::where('organization_name', 'like', '%'.$this->search.'%')
            ->orWhere('url', 'like', '%'.$this->search.'%')
            ->select('organization_name','id','url')
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
        $this->authorize('create-department-organization');
        $validated = $this->validate([
            'organization' => ['bail','required','max:50', new farsi_chs()],
            'url' => ['bail','required','starts_with:www.'],
            'image' => ['nullable','mimes:jpg,jpeg,png,webp','max:1024']
        ]);
        $imagePath = null; // Initialize imagePath to null
        if ($this->image) { // Check if an image was uploaded
            $imagePath = $this->image->store('organizations', 'public');
        }
        Organization::create([
            'organization_name' => $this->organization,
            'url' => $this->url,
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
        $this->authorize('update-department-organization',$organizationId);
        $organization = Organization::findOrFail($organizationId);
        $old_image_path = public_path('storage/'. $organization->image);
        if (file_exists($old_image_path)){
            unlink($old_image_path);
        }
        $this->validate([
            'organization' => ['bail','required','max:50', new farsi_chs()],
            'url' => ['bail','required','starts_with:www.'],
            'image' => ['nullable','mimes:jpg,jpeg,png,webp','max:1024']
        ]);
        if ($this->image) { // Check if a new image was uploaded
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
            $new_image = $this->image->store('organizations', 'public');
            Organization::where('id', $organizationId)->update([
                'organization_name' => $this->organization,
                'url' => $this->url,
                'image' => $new_image,
            ]);
        } else { // No new image, update only organization and url
            Organization::where('id', $organizationId)->update([
                'organization_name' => $this->organization,
                'url' => $this->url,
            ]);
        }
        $this->close();
        $this->reset();
        session()->flash('status', 'سامانه با موفقیت آپدیت شد');
    }

    public function openModalDelete($id)
    {
        $this->organization = Organization::where('id',$id)->value('organization_name');
        $this->organizationId = $id;
        $this->dispatch('crud-modal',name:'delete');
    }

    /**
     * @throws AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('delete-department-organization',$id);
        $organization = Organization::findOrFail($id);
        $old_image_path = public_path('storage/'. $organization->image);
        if (file_exists($old_image_path)){
            unlink($old_image_path);
        }
        $organization->delete();
        $this->close();
        session()->flash('status', 'سامانه با موفقیت حذف شد');
    }

    public function close()
    {
        $this->organizationId = '';
        $this->dispatch('close-modal');
        $this->redirectRoute('organizations.index');
    }


}
