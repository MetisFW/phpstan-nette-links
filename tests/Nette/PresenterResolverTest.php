<?php declare(strict_types = 1);

namespace PHPStanNetteLinks\Nette;

use PHPUnit\Framework\TestCase;

final class PresenterResolverTest extends TestCase
{

	/** @var PresenterResolver */
	private $presenterResolver;

	protected function setUp(): void
	{
		$this->presenterResolver = new PresenterResolver(
			[],
			new ContainerResolver(__DIR__ . '/containerLoader.php'),
			null
		);
	}

	public function testResolvePresenterName(): void
	{
		self::assertSame('Test', $this->presenterResolver->resolvePresenterName(':Test'));
		self::assertSame('TestModule:Test', $this->presenterResolver->resolvePresenterName(':TestModule:Test'));
		self::assertSame('TestModule:SubModule:Test', $this->presenterResolver->resolvePresenterName(':TestModule:SubModule:Test'));

		$currentPresenterClass = 'PHPStanNetteLinks\TestApp\Presenters\CurrentModule\CurrentPresenter';
		self::assertSame('CurrentModule:Current', $this->presenterResolver->resolvePresenterName('this', $currentPresenterClass));
		self::assertSame('CurrentModule:Test', $this->presenterResolver->resolvePresenterName('Test', $currentPresenterClass));
		self::assertSame('CurrentModule:SubModule:Test', $this->presenterResolver->resolvePresenterName('SubModule:Test', $currentPresenterClass));
	}

	public function testGetPresenterClassByName(): void
	{
		self::assertSame('PHPStanNetteLinks\TestApp\Presenters\TestPresenter', $this->presenterResolver->getPresenterClassByName(':Test'));
		self::assertSame('PHPStanNetteLinks\TestApp\Presenters\TestModule\TestPresenter', $this->presenterResolver->getPresenterClassByName(':TestModule:Test'));

		$currentPresenterClass = 'PHPStanNetteLinks\TestApp\Presenters\CurrentModule\CurrentPresenter';
		self::assertSame('PHPStanNetteLinks\TestApp\Presenters\CurrentModule\CurrentPresenter', $this->presenterResolver->getPresenterClassByName('this', $currentPresenterClass));
		self::assertSame('PHPStanNetteLinks\TestApp\Presenters\CurrentModule\TestPresenter', $this->presenterResolver->getPresenterClassByName('Test', $currentPresenterClass));
		self::assertSame('PHPStanNetteLinks\TestApp\Presenters\CurrentModule\SubModule\TestPresenter', $this->presenterResolver->getPresenterClassByName('SubModule:Test', $currentPresenterClass));
	}

}
