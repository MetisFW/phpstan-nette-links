<?php declare(strict_types = 1);

namespace PHPStanNetteLinks\TestApp\Presenters\CurrentModule;

use Nette\Application\UI\Presenter;
use PHPStanNetteLinks\TestApp\Components\CurrentComponent;

class CurrentPresenter extends Presenter
{

	public function actionDefault(): void
	{
	}

	public function actionWithParam(int $param): void
	{
	}

	public function handleSignal(): void
	{
	}

	public function handleSignalWithParam(int $param): void
	{
	}

	public function createComponentSubComponent(): CurrentComponent
	{
		return new CurrentComponent();
	}

}
