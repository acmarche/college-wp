<?php
/**
 * This file is part of wordpress application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 18/11/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AcMarche\College;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class AgendaCollege
{
    public function twig(): Environment
    {
        $path_template = __DIR__.DIRECTORY_SEPARATOR.'../templates';
        $loader = new FilesystemLoader($path_template);

        $twig = new Environment($loader);
        $extension = new TwigExtension();
        $twig->addExtension($extension);

        return $twig;
    }

    public function render(string $template, array $vars): void
    {
        $twig = $this->twig();

        echo $twig->render($template.'.html.twig', $vars);
    }

    public function createSession(): Session
    {
        $storage = new NativeSessionStorage(array(), new NativeFileSessionHandler());

        return new Session($storage);
    }


}
