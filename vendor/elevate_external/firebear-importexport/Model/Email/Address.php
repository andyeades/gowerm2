<?php
/**
 * @copyright: Copyright © 2019 Firebear Studio. All rights reserved.
 * @author : Firebear Studio <fbeardev@gmail.com>
 */
namespace Firebear\ImportExport\Model\Email;

/**
 * Class MailAddress
 */
class Address
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * MailAddress constructor
     *
     * @param string|null $email
     * @param string|null $name
     */
    public function __construct(
        ?string $email,
        ?string $name
    ) {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Name getter
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Email getter
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
