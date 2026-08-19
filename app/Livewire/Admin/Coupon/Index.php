<?php

namespace App\Livewire\Admin\Coupon;

use App\Enum\ModeType;
use App\Interface\Http\Coupons\CouponInterface;
use App\Models\Coupon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination,WithFileUploads;

    /**
     * Properties
     */
    public ModeType $mode = ModeType::DISPLAY;
    public ?Coupon $coupon = null;
    protected CouponInterface $protectedRepository;

    public string $code = '';
    public string $type = '';
    public string $value = '';
    public string $cart_value = '';
    public string $expiry_date = '';

    /**
     * Summary of boot
     * @param CouponInterface $protectedRepository
     * @return void
     */
    public function boot(CouponInterface $protectedRepository) {
        $this->protectedRepository = $protectedRepository;
    }

    /**
     * Summary of mount
     * @return void
     */
    public function mount()
    {
        $route = request()->route();

        $this->mode = match($route->getName()) {
            'admin.coupon' => ModeType::DISPLAY,
            'admin.coupon.create' => ModeType::CREATE,
            'admin.coupon.edit' => ModeType::EDIT,
            default => ModeType::DISPLAY,
        };

        if ($this->mode === ModeType::EDIT) {
            $coupon = $route->parameter('coupon');

            if($coupon instanceof Coupon) {
                $this->coupon = $coupon;
                $this->code = $coupon->code; ;
                $this->type = $coupon->type;
                $this->value = $coupon->value;
                $this->cart_value = $coupon->cart_value;
                $this->expiry_date = $coupon->expiry_date;
            }
        }
    }

    /**
     * Summary of render
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        $coupons = $this->protectedRepository->getAllPaginated(10);
        return view('livewire.admin.coupon.index',compact('coupons'))->layout('layouts.admin');
    }

    /**
     * Summary of rules
     * @return array{cart_value: string, code: string, expiry_date: string, type: string, value: string}
     */
    protected function rules() : array
    {
        return [
            'code' => 'required|string|max:255|unique:coupons,code,' . ($this->coupon?->id ?? 'NULL'),
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'cart_value' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
        ];
    }

    /**
     * Summary of storeCoupon
     * @return void
     */
    public function storeCoupon()
    {
        $this->mode = ModeType::CREATE;
        $this->validate();

        try {
            $data = $this->couponData();

            $this->protectedRepository->create($data);
            $this->resetForm();
            session()->flash('success', 'Coupon created successfully.');

            $this->redirectRoute('admin.coupons', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create coupon: ' . $e->getMessage());
        }
    }

    /**
     * Summary of updateCoupon
     * @return void
     */
    public function updateCoupon(): void
    {
        if (!$this->coupon) {
            session()->flash('error', 'Coupon not found.');
            return;
        }

        $this->mode = ModeType::EDIT;
        $this->validate();

        try {
            $data = $this->couponData();

            $this->protectedRepository->update($this->coupon, $data);
            $this->resetForm();
            session()->flash('success', 'Coupon updated successfully.');

            $this->redirectRoute('admin.coupons', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update coupon: ' . $e->getMessage());
        }
    }
    /**
     * Summary of deleteCoupon
     * @param mixed $id
     * @return void
     */
    public function deleteCoupon($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $this->protectedRepository->delete($coupon);
            session()->flash('success', 'Coupon deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() === '23000') {
                session()->flash('error', 'Cannot delete Coupon because it is associated with other records.');
            } else {
                session()->flash('error', 'Error deleting Coupon: ' . $e->getMessage());
            }
        } catch (\Throwable $e) {

            session()->flash('error', 'Error deleting Coupon: ' . $e->getMessage());
        }
    }

    /**
     * Summary of couponData
     * @return array{code: string, type: string, value: string, cart_value: string, expiry_date: string}
     */
    public function couponData()
    {
        return [
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'cart_value' => $this->cart_value,
            'expiry_date' => $this->expiry_date,
        ];
    }

    /**
     * Summary of resetForm
     * @return void
     */
    public function resetForm()
    {
        $this->code = '';
        $this->type = '';
        $this->value = '';
        $this->cart_value = '';
        $this->expiry_date = '';
        $this->mode = ModeType::DISPLAY;
    }
}
