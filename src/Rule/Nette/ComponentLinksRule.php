<?php declare(strict_types = 1);

namespace PHPStanNetteLinks\Rule\Nette;

use Nette\Application\UI\Component;
use Nette\Application\UI\Presenter;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;
use function array_slice;
use function count;
use function in_array;

/**
 * @extends LinksRule<MethodCall>
 */
class ComponentLinksRule extends LinksRule
{

	public function getNodeType(): string
	{
		return MethodCall::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		if (!$node->name instanceof Node\Identifier) {
			return [];
		}

		$methodName = $node->name->toString();
		$callerType = $scope->getType($node->var);
		$args = $node->getArgs();

		if (!isset($args[0])) {
			return [];
		}

		if ((new ObjectType(Component::class))->isSuperTypeOf($callerType)->no()) {
			return [];
		}

		if ((new ObjectType(Presenter::class))->isSuperTypeOf($callerType)->yes()) {
			return [];
		}

		if (!in_array($methodName, ['link', 'lazyLink', 'isLinkCurrent', 'redirect', 'redirectPermanent'], true)) {
			return [];
		}

		$destinationArg = $args[0];
		$paramArgs = array_slice($args, 1);

		$destinations = $this->extractDestinationsFromArg($scope, $destinationArg);
		if (count($paramArgs) === 1 && $scope->getType($paramArgs[0]->value)->isArray()->yes()) {
			$paramsVariants = $this->extractParamVariantsFromArrayArg($scope, $paramArgs[0] ?? null);
		} else {
			$paramsVariants = $this->extractParamVariantsFromArgs($scope, $paramArgs);
		}
		return $this->linkChecker->checkLinkVariants($scope, $callerType->getObjectClassNames(), $methodName, $destinations, $paramsVariants);
	}

}
