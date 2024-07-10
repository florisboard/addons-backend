<?php

namespace App\Filament\Resources\ChangeProposalResource\Pages;

use App\Enums\StatusEnum;
use App\Filament\Resources\ChangeProposalResource;
use App\Models\ChangeProposal;
use App\Models\Project;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditChangeProposal extends EditRecord
{
    protected static string $resource = ChangeProposalResource::class;

    /**
     * @throws \JsonException
     */
    protected function mergeChangeProposal(ChangeProposal $changeProposal): void
    {
        $collection = collect(json_decode($changeProposal->data, false, 512, JSON_THROW_ON_ERROR));
        /** @var Project $project */
        $project = $changeProposal->model;

        $project->update($collection->except('maintainers')->toArray());
        $project->maintainers()->sync($collection->get('maintainers'));

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
