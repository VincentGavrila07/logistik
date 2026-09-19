<?php

namespace App\Livewire\Quotation;

use App\Models\Customer;
use App\Models\User;
use Livewire\Component;

class Quotation extends Component
{
    public $port_of_loading;
    public $port_of_discharge;
    public $sclient;



    public $clients;
    public $dagentsJob;
    public $ogentsJob;
    public $carriers;
    public $employe;
    public $client_id;
    public $deliveryAgent = "";
    public $originAgent = "";
    public array $ports = [];

    public function mount()
    {
        $this->clients = Customer::whereJsonContains('roles', 'client')->get();
        $this->dagentsJob = Customer::whereJsonContains('roles', 'delivery_agent')->get();
        $this->ogentsJob = Customer::whereJsonContains('roles', 'origin_agent')->get();
        $this->carriers = Customer::whereJsonContains('roles', 'carrier')->get();
        $this->employe = User::all('id', 'name');
    }
    public function render()
    {
        return view('livewire.quotation.quotation');
    }
}
