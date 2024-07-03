<?php

namespace App\Filament\Resources\ChangeProposalResource\Pages;

use App\Enums\StatusEnum;
use App\Filament\Resources\ChangeProposalResource;
use App\Models\ChangeProposal;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditChangeProposal extends EditRecord
{
    protected static string $resource = ChangeProposalResource::class;

    protected function mergeChangeProposal(ChangeProposal $changeProposal): void
    {
        $changeProposal->model()->update($changeProposal->data);

        Notification::make()
            ->title('Change Proposal merged successfully.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            //            Actions\DeleteAction::make(),
            Actions\Action::make('merge')
                ->hidden(fn (ChangeProposal $changeProposal) => $changeProposal->status !== StatusEnum::Approved)
                ->action(function (ChangeProposal $changeProposal) {
                    $this->mergeChangeProposal($changeProposal);
                })
                ->requiresConfirmation(),
        ];
    }
}
