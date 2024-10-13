<?php

declare(strict_types=1);

namespace app\common\rbac;

class CollectionRolls
{

    public const RBAC_ON = false;

    public const ROLE_ROOT = 'admin';
    public const ROLE_PRIMARY = 'primary';
    public const ROLE_SECONDARY = 'secondary';
    public const ROLE_MASTER = 'master';
    public const ROLE_ENGINEER = 'engineer';
    public const ROLE_REPORT_CARD = 'record_card';

    public const ROLE_HEAD_DEPARTMENT = 'head_department';
    public const ROLE_HEAD_DEPARTMENT_SECONDARY = 'head_department_secondary';
    public const ROLE_COMMODITY_EXPERT = 'commodity_expert';

    public const ROLE_PRIMARY_CIL = 'primary_cil';
    public const ROLE_SECONDARY_CIL = 'secondary_cil';
    public const ROLE_ENGINEER_CIL = 'engineer_cil';
    public const ROLE_VERIFIER = 'verifier';

    /**
     * @return string[]
     */
    public static function getListRole(): array
    {
        return [
            self::ROLE_ROOT => 'Администратор',
            self::ROLE_PRIMARY => 'Начальник цеха',
            self::ROLE_SECONDARY => 'Зам. начальника цеха',
            self::ROLE_MASTER => 'Мастер',
            self::ROLE_ENGINEER => 'Инженер',
            self::ROLE_REPORT_CARD => 'Табельщик',
            self::ROLE_HEAD_DEPARTMENT => 'Начальник отдела',
            self::ROLE_HEAD_DEPARTMENT_SECONDARY => 'Зам. начальника отдела',
            self::ROLE_COMMODITY_EXPERT => 'Товаровед',

            self::ROLE_PRIMARY_CIL => 'Начальник ЦИЛ',
            self::ROLE_SECONDARY_CIL => 'Зам. начальника ЦИЛ',
            self::ROLE_ENGINEER_CIL => 'Инженер ЦИЛ',
            self::ROLE_VERIFIER => 'Поверитель',
        ];
    }

    /**
     * @param string $role
     * @return string
     */
    public static function getRoleName(string $role): string
    {
        return self::getListRole()[$role];
    }

}
