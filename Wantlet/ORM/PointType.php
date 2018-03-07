<?php
namespace Wantlet\ORM;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQL57Platform;

/**
 * Mapping type for spatial POINT objects
 *
 * Some of these functions are from:
 * http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/advanced-field-value-conversion-using-custom-mapping-types.html#the-mapping-type
 */
class PointType extends Type {
    const POINT = 'point';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName() {
        return self::POINT;
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
        return 'POINT';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) {
        //Null fields come in as empty strings
        if($value == '') {
            return null;
        }

        if ($platform instanceof MySQL57Platform)
        {
        	list($longitude, $latitude) = sscanf($value, 'POINT(%f %f)');

        	return new \Wantlet\ORM\Point($latitude, $longitude);
        }

        $data = unpack('x/x/x/x/corder/Ltype/dlat/dlon', $value);
        return new \Wantlet\ORM\Point($data['lat'], $data['lon']);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        if (!$value) return;

        if ($platform instanceof MySQL57Platform)
        	return sprintf("POINT(%F %F)", $value->getLongitude(), $value->getLatitude());

        return pack('xxxxcLdd', '0', 1, $value->getLatitude(), $value->getLongitude());
    }



    /**
     * Does working with this column require SQL conversion functions?
     *
     * This is a metadata function that is required for example in the ORM.
     * Usage of {@link convertToDatabaseValueSQL} and
     * {@link convertToPHPValueSQL} works for any type and mostly
     * does nothing. This method can additionally be used for optimization purposes.
     *
     * @return boolean
     */
    public function canRequireSQLConversion()
    {
        return true;
    }



    /**
     * Modifies the SQL expression (identifier, parameter) to convert to a PHP value.
     *
     * @param string                                    $sqlExpr
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToPHPValueSQL($sqlExpr, $platform)
    {
        return sprintf('AsText(%s)', $sqlExpr);
    }



    /**
     * Modifies the SQL expression (identifier, parameter) to convert to a database value.
     *
     * @param string                                    $sqlExpr
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
    {
        return sprintf('PointFromText(%s)', $sqlExpr);
    }
}
