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

class AnnotationDriver{

	private $reader;

	public function __construct($reader)
	{
		$this->reader = $reader;//Получаем читалку аннотаций
	}
	/**
	 * Это событие возникнет при вызове любого контроллера
	 */
	public function onKernelController(FilterControllerEvent $event)
	{

		if (!is_array($controller = $event->getController())) { //Выходим, если нет контроллера
			return;
		}

		$object = new \ReflectionObject($controller[0]);// Получаем контроллер
		$method = $object->getMethod($controller[1]);// Получаем метод

		foreach ($this->reader->getMethodAnnotations($method) as $configuration) { //Начинаем читать аннотации
			if(isset($configuration->perm)){//Если прочитанная аннотация наша, то выполняем код ниже
				$user = $controller[0]->get('security.context')->getToken()->getUser()->getUserName();
				/* @var $user User */
				if(!$user->hasPermission($configuration->perm)){
					//Если после проверки доступа нет, то выдаём 403
					throw new AccessDeniedHttpException();

				}

			}
		}
	}
}
