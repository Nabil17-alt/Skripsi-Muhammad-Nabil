<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::whereNotIn('type', ['dana', 'ovo', 'shopeepay'])
            ->orderBy('id')
            ->get();

        $bankAccounts = BankAccount::with('method')
            ->whereHas('method', function ($query) {
                $query->whereIn('type', ['bank_transfer', 'installment']);
            })
            ->orderBy('payment_method_id')
            ->orderBy('bank_name')
            ->get();

        return view('admin.bank_accounts.index', compact('bankAccounts', 'paymentMethods'));
    }

    public function create()
    {
        $methods = PaymentMethod::where('type', 'bank_transfer')->get();

        return view('admin.bank_accounts.create', compact('methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:tb_payment_methods,id',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_holder' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'payment_method_id',
            'bank_name',
            'account_number',
            'account_holder',
        ]);

        // Beberapa channel seperti QRIS bisa tidak memiliki nomor rekening/atas nama
        if ($data['account_number'] === null) {
            $data['account_number'] = '';
        }
        if ($data['account_holder'] === null) {
            $data['account_holder'] = '';
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('payment_channels', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);

        BankAccount::create($data);

        return redirect()->route('admin.bank-accounts.index');
    }

    public function edit(BankAccount $bankAccount)
    {
        $methods = PaymentMethod::where('type', 'bank_transfer')->get();

        return view('admin.bank_accounts.edit', compact('bankAccount', 'methods'));
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:tb_payment_methods,id',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_holder' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'payment_method_id',
            'bank_name',
            'account_number',
            'account_holder',
        ]);

        if ($data['account_number'] === null) {
            $data['account_number'] = '';
        }
        if ($data['account_holder'] === null) {
            $data['account_holder'] = '';
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('payment_channels', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $bankAccount->update($data);

        return redirect()->route('admin.bank-accounts.index');
    }

    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();

        return redirect()->route('admin.bank-accounts.index');
    }

    public function toggleMethodStatus(PaymentMethod $method)
    {
        $method->is_active = !$method->is_active;
        $method->save();

        return redirect()->route('admin.bank-accounts.index')
            ->with('status', 'Status metode ' . $method->name . ' berhasil diubah.');
    }

    public function updateInstallmentSettings(Request $request)
    {
        $request->validate([
            'installment_min_amount' => 'required|integer|min:0',
            'installment_dp_percent' => 'required|integer|min:0|max:100',
        ]);

        $envPath = base_path('.env');

        if (file_exists($envPath)) {
            $content = file_get_contents($envPath);

            // Replace or append INSTALLMENT_MIN_AMOUNT
            if (str_contains($content, 'INSTALLMENT_MIN_AMOUNT=')) {
                $content = preg_replace('/INSTALLMENT_MIN_AMOUNT=\d+/', 'INSTALLMENT_MIN_AMOUNT=' . $request->installment_min_amount, $content);
            } else {
                $content .= "\nINSTALLMENT_MIN_AMOUNT=" . $request->installment_min_amount;
            }

            // Replace or append INSTALLMENT_DP_PERCENT
            if (str_contains($content, 'INSTALLMENT_DP_PERCENT=')) {
                $content = preg_replace('/INSTALLMENT_DP_PERCENT=\d+/', 'INSTALLMENT_DP_PERCENT=' . $request->installment_dp_percent, $content);
            } else {
                $content .= "\nINSTALLMENT_DP_PERCENT=" . $request->installment_dp_percent;
            }

            file_put_contents($envPath, $content);

            // Clear the config cache
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
        }

        return redirect()->route('admin.bank-accounts.index')
            ->with('status', 'Pengaturan cicilan berhasil diperbarui.');
    }
}

