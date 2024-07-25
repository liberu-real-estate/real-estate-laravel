<?php

namespace App\Filament\Tenant\Resources;

use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Tenant\Resources\PaymentResource\Pages;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Exceptions\IncompletePayment;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Payments';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\DatePicker::make('payment_date')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ]),
                        Forms\Components\Select::make('payment_method')
                            ->required()
                            ->options([
                                'credit_card' => 'Credit Card',
                                'bank_transfer' => 'Bank Transfer',
                                'paypal' => 'PayPal',
                            ]),
                    ]),
                Card::make()
                    ->schema([
                        Placeholder::make('stripe_payment')
                            ->label('Stripe Payment')
                            ->content(function ($record) {
                                if ($record && $record->status === 'pending') {
                                    return view('stripe.payment-button', ['payment' => $record]);
                                }
                                return 'Stripe payment is only available for pending payments.';
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tenant_id', auth()->id());
    }

    public static function handleStripePayment($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $user = Auth::user();

        try {
            $user->charge($payment->amount * 100, $payment->payment_method);
            $payment->status = 'completed';
            $payment->save();
            return redirect()->back()->with('success', 'Payment processed successfully.');
        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('filament.resources.payments.view', $payment)]
            );
        } catch (\Exception $e) {
            $payment->status = 'failed';
            $payment->save();
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
}
