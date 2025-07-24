<?php

namespace App\Livewire\admin;

use App\Models\Organization;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class OrganizationTable extends Component
{
    use WithPagination,WithFileUploads, WithoutUrlPagination;
    public $organization = '';
    public string $url = '';
    public $image;
    public ?string $search = '';
    public $organizationId = '';
    public $organizationName;

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
        $search = trim($this->search);
        return DB::table('organizations')
            ->where('organization_name', 'like', "%{$search}%")
            ->orWhere('url', 'like', "%{$search}%")
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
        $validated = Validator::make(
            ['organization' => $this->organization, 'url' => $this->url, 'image' => $this->image,
            ],
            ['organization' => ['bail', 'required', 'max:250'],
                'url' => ['bail', 'required', 'starts_with:www.'],
                'image' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            ],
            ['organization.required' => 'نام سازمان اجباری است.',
                'url.required' => 'آدرس وب‌سایت اجباری است.', 'url.starts_with' => 'آدرس باید با www. شروع شود.',
                'image.mimes' => 'فرمت تصویر باید jpg, jpeg, png یا webp باشد.',
                'image.max' => 'حجم تصویر نباید بیشتر از 1MB باشد.',
            ]
        );

        $imagePath = null;
        if ($this->image) {
            $imagePath = $validated['image']->store('organizations', 'public');
        }

        Organization::create([
            'organization_name' => $validated['organization'],
            'url' => $validated['url'],
            'image' => $imagePath,
        ]);
        $this->close();
        session()->flash('status', 'سامانه جدید با موفقیت ثبت شد');
    }

    /**
     * @throws AuthorizationException
     */
    public function updateOrg()
    {
        $organization = Organization::findOrFail($this->organizationId);

        $validated = Validator::make(
            [
                'organization' => $this->organization,
                'url' => $this->url,
                'image' => $this->image,
            ],
            [
                'organization' => ['bail', 'required', 'max:250'],
                'url' => ['bail', 'required', 'starts_with:www.'],
                'image' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            ],
            [
                'organization.required' => 'نام سازمان اجباری است.',
                'url.required' => 'آدرس وب‌سایت اجباری است.',
                'url.starts_with' => 'آدرس باید با www. شروع شود.',
                'image.mimes' => 'فرمت تصویر باید jpg, jpeg, png یا webp باشد.',
                'image.max' => 'حجم تصویر نباید بیشتر از 1MB باشد.',
            ]
        );

        // Handle image upload
        if ($validated['image']) {
            if (!empty($organization->image)) {
                $oldImagePath = public_path('storage/' . $organization->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $validated['image'] = $validated['image']->store('organizations', 'public');
        } else {
            // If no new image uploaded, preserve the old one
            unset($validated['image']);
        }

        // Update organization
        $organization->update($validated);

        $this->close();
        session()->flash('status', 'سامانه با موفقیت آپدیت شد');
    }
    public function openModalDelete($id)
    {
        $this->organization = Organization::findOrFail($id);
        $this->organizationId = $id;
        $this->organizationName = $this->organization->organization_name;
        $this->dispatch('crud-modal', name: 'delete');
    }
    public function close()
    {
        $this->organizationId = '';
        $this->dispatch('close-modal');
        $this->redirectRoute('organizations');
    }


}
