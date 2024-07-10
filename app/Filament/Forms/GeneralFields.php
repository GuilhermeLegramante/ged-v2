<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Leandrocfe\FilamentPtbrFormFields\Cep;

class GeneralFields
{
    public static function note(): Textarea
    {
        return Textarea::make('note')
            ->label('Observação')
            ->maxLength(65535)
            ->columnSpanFull();
    }

    public static function name(): TextInput
    {
        return TextInput::make('name')
            ->label('Nome')
            ->required()
            ->columnSpanFull()
            ->maxLength(255);
    }

    public static function email(): TextInput
    {
        return TextInput::make('email')
            ->email()
            ->label('E-mail')
            ->maxLength(255);
    }

    public static function address(): Fieldset
    {
        return Fieldset::make('Endereço')
            ->relationship('address')
            ->schema([
                Cep::make('postal_code')
                    ->label('CEP')
                    ->live(onBlur: true)
                    ->viaCep(
                        mode: 'suffix',
                        errorMessage: 'CEP inválido.',
                        setFields: [
                            'street' => 'logradouro',
                            'number' => 'numero',
                            'complement' => 'complemento',
                            'district' => 'bairro',
                            'city' => 'localidade',
                            'state' => 'uf',
                        ]
                    ),
                TextInput::make('street')->label('Rua')->columnSpan(1),
                TextInput::make('number')->label('N°'),
                TextInput::make('complement')->label('Complemento'),
                TextInput::make('reference')->label('Referência'),
                TextInput::make('district')->label('Bairro'),
                TextInput::make('city')->label('Cidade'),
                TextInput::make('state')->label('UF'),
            ])
            ->columns(4);
    }
}
