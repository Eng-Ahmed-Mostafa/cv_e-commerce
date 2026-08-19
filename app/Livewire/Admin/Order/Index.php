<?php

namespace App\Livewire\Admin\Order;

use App\Enum\ModeTypeDetial;
use App\Interface\Http\Orders\OrderInterface;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    /**
     * Properties
     */

    private OrderInterface $orderRepository;
    public ModeTypeDetial $mode;
    public ?Order $order = null;


    public function boot(OrderInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function mount($id = null)
    {
        $route = request()->route();
        $this->mode = match ($route->getName()) {
            'admin.orders.details' => ModeTypeDetial::DETAILS,
            'admin.orders' => ModeTypeDetial::DISPLAY,
            default => ModeTypeDetial::DISPLAY,
        };

        if($this->mode === ModeTypeDetial::DETAILS) {
            $this->order = $this->orderRepository->getAllOrdersWithDetails($id);
        }
    }

    public function render()
    {
        $orders = $this->orderRepository->getAllOrdersPaginated();
        return view('livewire.admin.order.index',compact('orders'))->layout('layouts.admin');
    }
}
