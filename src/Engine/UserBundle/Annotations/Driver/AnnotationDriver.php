<?php
/**
 * Created by PhpStorm.
 * User: abirillo
 * Date: 06.12.16
 * Time: 19:38
 */

namespace Engine\UserBundle\Annotations\Driver;

use Doctrine\Common\Annotations\Reader;//Вот эта штука как раз и читает аннотации
use Engine\UserBundle\Entity\User;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;//Подключаем нужный компонент ядра
use Engine\UserBundle\Annotations;//Юзаем свою аннотацию
//use Engine\UserBundle\Security\Permission; //В этом классе я проверяю соответствие permission to user
use Symfony\Component\HttpFoundation\Response; // В нашем примере я просто буду выводить 403, если нет доступа

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Security;

class AnnotationDriver{

	private $reader;
	private $security;

	public function __construct(Reader $reader, Security $security)
	{
		$this->reader = $reader;//Получаем читалку аннотаций
		$this->security = $security;//Получаем security
	}

    /**
     * Это событие возникнет при вызове любого контроллера
     * @throws \ReflectionException
     */
	public function onKernelController(FilterControllerEvent $event)
	{

        if (!is_array($controllers = $event->getController())) {
            return null;
        }

        list($controller, $methodName) = $controllers;

        $reflectionClass = new \ReflectionClass($controller);
        $classAnnotation = $this->reader->getClassAnnotation($reflectionClass, Annotations\Permissions::class);

        $reflectionObject = new \ReflectionObject($controller);
        $reflectionMethod = $reflectionObject->getMethod($methodName);
        $methodAnnotation = $this->reader->getMethodAnnotation($reflectionMethod, Annotations\Permissions::class);

        if (!($classAnnotation || $methodAnnotation)) {
            return null;
        }

		if ($methodAnnotation) {
            /* @var $user User */
		    $user = $this->security->getToken()->getUser();
            if(!$user->hasPermission($methodAnnotation->perm)){
                //Если после проверки доступа нет, то выдаём 403
                throw new AccessDeniedHttpException();

            }
		}
	}
}
