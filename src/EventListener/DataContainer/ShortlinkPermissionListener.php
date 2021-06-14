<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Symfony\Component\Security\Core\Security;

/**
 * @Callback(table="tl_terminal42_shortlink", target="config.onload")
 */
class ShortlinkPermissionListener
{
    private const TABLE = 'tl_terminal42_shortlink';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(): void
    {
        if ($this->security->isGranted('contao_user.can_edit_fields', self::TABLE)) {
            return;
        }

        $GLOBALS['TL_DCA'][self::TABLE]['config']['closed'] = true;
        $GLOBALS['TL_DCA'][self::TABLE]['config']['notEditable'] = true;
        $GLOBALS['TL_DCA'][self::TABLE]['config']['notDeletable'] = true;
        $GLOBALS['TL_DCA'][self::TABLE]['config']['notCopyable'] = true;

        unset(
            $GLOBALS['TL_DCA'][self::TABLE]['list']['global_operations']['all'],
            $GLOBALS['TL_DCA'][self::TABLE]['list']['operations']['edit'],
            $GLOBALS['TL_DCA'][self::TABLE]['list']['operations']['copy'],
            $GLOBALS['TL_DCA'][self::TABLE]['list']['operations']['delete']
        );
    }
}
