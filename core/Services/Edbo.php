<?php
namespace Core\Services;

use Core\Database;

Class Edbo{

    private static array $data=[
        "university_name"=>"",
        "university_id"=>"",
        "university_parent_id"=>"",
        "university_short_name"=>"",
        "university_name_en"=>"",
        "is_from_crimea"=>"",
        "registration_year"=>"",
        "university_type_name"=>"",
        "university_financing_type_name"=>"",
        "university_governance_type_name"=>"",
        "post_index_u"=>"",
        "katottgcodeu"=>"",
        "katottg_name_u"=>"",
        "region_name_u"=>"",
        "university_address_u"=>"",
        "university_phone"=>"",
        "university_email"=>"",
        "university_site"=>"",
        "university_director_post"=>"",
        "university_director_fio"=>"",
        "close_date"=>""
    ];

    private static function DB()
    {
        return Database::getInstance();
    }

    /**
     * @param int $id
     * @return array
     */
    public static function getOrgData(int $id): array
    {
        $data = self::DB()->query("SELECT * FROM edbo WHERE university_id=:id",[":id"=>$id], true);
        return array_merge(self::$data,$data[0]);
    }

}
