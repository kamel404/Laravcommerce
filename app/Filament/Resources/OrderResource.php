<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([ 
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user','name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('payment_method')
                        ->label('Payment Method')
                        ->options([
                            'stripe' => 'Stripe',
                            'cod' => 'Cash On Delivery',
                        ])
                        ->required(),

                    Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed'
                        ]) 
                        -> required()
                        ->default('pending'),
                    
                    ToggleButtons::make('status')
                        ->inline()
                        ->default('new')
                        ->required()
                        ->options([
                            'new' => 'New',
                            'processing' => 'Processing',
                            'delivered' => 'Delivered',
                            'shipped' => 'Shipped',
                            'canceled' => 'Canceled'
                        ])
                        ->colors([
                            'new' => 'info',
                            'processing' => 'warning',
                            'delivered' => 'success',
                            'shipped' => 'success',
                            'canceled' => 'danger'
                        ])
                        ->icons([
                            'new' => 'heroicon-m-sparkles',
                            'processing' => 'heroicon-m-arrow-path',
                            'delivered' => 'heroicon-m-truck',
                            'shipped' => 'heroicon-m-check-badge',
                            'canceled' => 'heroicon-m-x-circle'
                        ]),

                    Select::make("currency")
                        ->options([
                            'usd' => 'USD',
                            'lbp' => 'LBP',
                        ]) ->required()
                        ->default('lbp'),

                    Select::make('shipping_method')->options([
                        'fedex' => 'FedEx',
                        'ups' => 'UPS',
                        'dhl' => 'DHL',
                    ])->required(),   
                    
                    Textarea::make('notes')
                        ->columnSpanFull(),
                    
                ])->columns(2)
            ]) -> columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
