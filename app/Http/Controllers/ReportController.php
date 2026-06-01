<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Vehicle;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Booking;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function download(string $type)
    {
        $data = [
            'type' => ucfirst($type),
            'generated_at' => now()->format('d M Y - H:i:s'),
        ];

        if ($type === 'fleet') {
            $data['title'] = 'Fleet Operations Summary Report';
            $data['total'] = Vehicle::count();
            $data['available'] = Vehicle::where('status', 'available')->count();
            $data['po'] = Vehicle::where('status', 'po')->count();
            $data['maintenance'] = Vehicle::where('status', 'maintenance')->count();
            $data['hold'] = Vehicle::where('status', 'hold')->count();
            $data['vehicles'] = Vehicle::limit(15)->get();
        } elseif ($type === 'sales') {
            $data['title'] = 'Sales & B2B Pipeline Report';
            $data['total_clients'] = Client::count();
            $data['total_bookings'] = Booking::count();
            $data['clients'] = Client::limit(10)->get();
            $data['bookings'] = Booking::with(['client', 'vehicle'])->limit(10)->get();
        } else {
            $data['title'] = 'Finance & Accounts Recap Report';
            $data['total_invoiced'] = Invoice::sum('total_amount');
            $data['total_outstanding'] = Invoice::sum('remaining_balance');
            $data['total_payments'] = Payment::sum('amount');
            $data['invoices'] = Invoice::with('booking.client')->limit(10)->get();
            $data['payments'] = Payment::with('invoice.booking.client')->limit(10)->get();
        }

        $pdf = Pdf::loadView('reports.pdf_template', $data);
        return $pdf->download("Golden_Bird_Report_{$type}_" . now()->format('Ymd_His') . ".pdf");
    }
}
