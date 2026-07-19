<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $lostCustomerDays = Setting::getValue('lost_customer_days', 90);

        return view('settings.edit', compact('lostCustomerDays'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'lost_customer_days' => 'required|integer|min:1',
        ]);

        Setting::setValue('lost_customer_days', $request->lost_customer_days);

        return redirect()->route('settings.edit')->with('success', 'Settings updated successfully.');
    }
}
