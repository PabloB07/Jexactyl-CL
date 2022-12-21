<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\View\Factory;
use Pterodactyl\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pterodactyl\Models\TicketMessage;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Tickets\TicketStatusRequest;
use Pterodactyl\Http\Requests\Admin\Tickets\TicketMessageRequest;

class TicketsController extends Controller
{
    public function __construct(protected Factory $view, protected AlertsMessageBag $alert)
    {
    }

    /**
     * List the available tickets.
     */
    public function index(): View
    {
        return $this->view->make('admin.tickets.index', ['tickets' => Ticket::all()]);
    }

    /**
     * View a specific ticket.
     */
    public function view(int $id): View
    {
        return $this->view->make('admin.tickets.view', [
            'ticket' => Ticket::findOrFail($id),
            'messages' => TicketMessage::where('ticket_id', $id)->get(),
        ]);
    }

    /**
     * Update the status of a ticket.
     */
    public function status(TicketStatusRequest $request, int $id): RedirectResponse
    {
        Ticket::findOrFail($id)->update(['status' => $request->input('status')]);

        TicketMessage::create([
            'user_id' => 0,
            'ticket_id' => $id,
            'content' => 'Status do Ticket foi definido para ' . $request->input('status'),
        ]);

        return redirect()->route('admin.tickets.view', $id);
    }

    /**
     * Add a message to the ticket.
     */
    public function message(TicketMessageRequest $request, int $id): RedirectResponse
    {
        TicketMessage::create([
            'user_id' => $request->user()->id,
            'ticket_id' => $id,
            'content' => $request->input('content'),
        ]);

        return redirect()->route('admin.tickets.view', $id);
    }

    /**
     * Deletes a ticket and the associated messages.
     */
    public function delete(int $id): RedirectResponse
    {
        Ticket::findOrFail($id)->delete();
        TicketMessage::where('ticket_id', $id)->delete();

        $this->alert->success('Ticket ' . $id . ' foi excluído.')->flash();

        return redirect()->route('admin.tickets.index');
    }
}
