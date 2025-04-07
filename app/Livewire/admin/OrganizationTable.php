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
//    private function validateOrgData()
//    {
//        return $this->validate([
//            'organization' => ['bail', 'required', 'max:50', new farsi_chs()],
//            'url' => ['bail', 'required', 'starts_with:www.'],
//            'image' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:1024']
//        ]);
//    }
//    public function openModal($type, $id = null)
//    {
//        if ($id) {
//            $organization = Organization::findOrFail($id);
//            $this->organization = $organization->organization_name;
//            $this->url = $organization->url;
//            $this->organizationId = (int) $id;
//        }
//        $this->dispatch('crud-modal', name: $type);
//    }


//    public function save()
//    {
//        $validated = $this->validateOrgData();
//
//        // Ensure 'organization_name' is included
//        $validated['organization_name'] = $this->organization;
//        $validated['image'] = $this->image ? $this->image->store('organizations', 'public') : null;
//
//        Organization::updateOrCreate(
//            ['id' => $this->organizationId],
//            $validated
//        );
//
//        $this->resetForm();
//        session()->flash('status', $this->organizationId ? 'سامانه آپدیت شد' : 'سامانه جدید ثبت شد');
//    }
//    public function delete($id)
//    {
//        $organization = Organization::findOrFail($id);
//        $this->deleteImage($organization->image);
//        $organization->delete();
//
//        $this->resetForm();
//        session()->flash('status', 'سامانه حذف شد');
//    }
//
//    private function deleteImage($imagePath)
//    {
//        if ($imagePath) {
//            @unlink(public_path("storage/{$imagePath}"));
//        }
//    }

//    private function resetForm()
//    {
//        $this->reset(['organization', 'url', 'image', 'organizationId']);
//    }
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

//    public function createOrg()
//    {
//        $this->validateOrgData();
//        $validated['image'] = $this->uploadImage($this->image);
//
//        Organization::create([
//            'organization_name' => $this->organization_name,
//            'url' => $this->url,
//            'image' => $this->image ? $this->image->store('images', 'public') : null,
//        ]);
//
//        $this->reset(['organization_name', 'url', 'image']);
//        session()->flash('status', 'سامانه جدید با موفقیت ثبت شد');
//    }

//    public function updateOrg($organizationId)
//    {
//        $organization = Organization::findOrFail($organizationId);
//        $validated = $this->validateOrgData();
//
//        if ($this->image) {
//            $this->deleteImage($organization->image);
//            $validated['image'] = $this->uploadImage($this->image);
//        }
//
//        $organization->update($validated);
//
//        $this->reset(['organization_name', 'url', 'image']);
//        session()->flash('status', 'سامانه با موفقیت آپدیت شد');
//    }

//    public function delete($id)
//    {
//        $organization = Organization::findOrFail($id);
//        $this->deleteImage($organization->image);
//        $organization->delete();
//
//        $this->reset(['organization_name', 'url', 'image']);
//        session()->flash('status', 'سامانه با موفقیت حذف شد');
//    }
////
//
//
//    private function uploadImage($image)
//    {
//        return $image ? $image->store('organizations', 'public') : null;
//    }
//
//    private function deleteImage($imagePath)
//    {
//        $fullPath = public_path("storage/{$imagePath}");
//        if ($imagePath && file_exists($fullPath)) {
//            unlink($fullPath);
//        }
//    }



    /**
     * @throws AuthorizationException
     */
    public function createOrg()
    {
//        $this->authorize('create-department-organization');
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
//        $this->authorize('update-department-organization',$organizationId);
        $organization = Organization::findOrFail($organizationId);
//        $old_image_path = public_path('storage/'. $organization->image);
//        if (file_exists($old_image_path)){
//            unlink($old_image_path);
//        }
        $validated = $this->validate([
            'organization' => ['bail','required','max:50', new farsi_chs()],
            'url' => ['bail','required','starts_with:www.'],
            'image' => ['nullable','mimes:jpg,jpeg,png,webp','max:1024']
        ]);
        // Handle Image Upload
        if ($this->image) { // If a new image is uploaded
            // Delete old image only if it exists
            if (!empty($organization->image)) {
                $oldImagePath = public_path('storage/' . $organization->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // Upload new image
            $validated['image'] = $this->image->store('organizations', 'public');
        }
        // Update organization
        $organization->update($validated);

        // Reset and close modal
        $this->reset(['organization', 'url', 'image']);
//        $this->dispatch('crud-modal', name: 'close'); // Close modal
//        if ($this->image) { // Check if a new image was uploaded
//            if (file_exists($old_image_path)) {
//                unlink($old_image_path);
//            }
//            $new_image = $this->image->store('organizations', 'public');
//            Organization::where('id', $organizationId)->update([
//                'organization_name' => $this->organization,
//                'url' => $this->url,
//                'image' => $new_image,
//            ]);
//        } else { // No new image, update only organization and url
//            Organization::where('id', $organizationId)->update([
//                'organization_name' => $this->organization,
//                'url' => $this->url,
//            ]);
//        }
        $this->close();
//        $this->reset();
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
//        $this->authorize('delete-department-organization',$id);
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
        $this->redirectRoute('organizations');
    }


}
