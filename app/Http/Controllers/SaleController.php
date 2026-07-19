<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\StoreSaleRequest;
use App\Models\Sale;
use App\Services\CustomerService;
use App\Services\ProductService;
use App\Services\SaleService;

class SaleController extends Controller
{
    public function __construct(protected SaleService $saleService,protected ProductService $productService,protected CustomerService $customerService) {

    }

    public function index()
    {
        $sales = $this->saleService->list(request()->only('search', 'customer_id', 'employee_id', 'date_from', 'date_to'));

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = $this->customerService->list()->items();
        $products = $this->productService->getActiveProducts();

        return view('sales.create', compact('customers', 'products'));
    }

    public function store(StoreSaleRequest $request)
    {
        $data = $request->validated();
        $data['employee_id'] = auth()->id();

        $sale = $this->saleService->create($data);

        return redirect()->route('sales.show', $sale)->with('success', 'Sale created successfully.');
    }

    public function show(Sale $sale)
    {
        $sale = $this->saleService->find($sale->id);

        return view('sales.show', compact('sale'));
    }
}
