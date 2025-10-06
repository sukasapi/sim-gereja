<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ProposalRecipient;
use Illuminate\Http\Request;

class PublicProposalController extends Controller
{
    public function index()
    {
        $recipients = ProposalRecipient::with('proposal')
            ->latest()
            ->paginate(10);
        return view('public.proposals.index', compact('recipients'));
    }

    public function show(Proposal $proposal)
    {
        return view('public.proposals.show', compact('proposal'));
    }
} 