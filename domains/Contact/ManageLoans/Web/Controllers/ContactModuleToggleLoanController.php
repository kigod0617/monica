<?php

namespace App\Contact\ManageLoans\Web\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Contact\ManageLoans\Services\ToggleLoan;
use App\Contact\ManageLoans\Web\ViewHelpers\ModuleLoanViewHelper;

class ContactModuleToggleLoanController extends Controller
{
    public function update(Request $request, int $vaultId, int $contactId, int $loanId)
    {
        $data = [
            'account_id' => Auth::user()->account_id,
            'author_id' => Auth::user()->id,
            'vault_id' => $vaultId,
            'contact_id' => $contactId,
            'loan_id' => $loanId,
        ];

        $loan = (new ToggleLoan)->execute($data);
        $contact = Contact::find($contactId);

        return response()->json([
            'data' => ModuleLoanViewHelper::dtoLoan($loan, $contact, Auth::user()),
        ], 200);
    }
}
