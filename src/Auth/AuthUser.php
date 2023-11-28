<?php declare(strict_types=1);

namespace Hyvor\Helper\Auth;

/**
 * @phpstan-type LoginUserArray array{
 *  id: int,
 *  username: string,
 *  name: string,
 *  email: string,
 *  picture_url?: string,
 *  location?: string,
 *  bio?: string,
 *  website_url?: string,
 *  sub?: string,
 * }
 *
 * @phpstan-type LoginUserArrayPartial array{
 * id?: int,
 * username?: string,
 * name?: string,
 * email?: string,
 * picture_url?: string,
 * location?: string,
 * bio?: string,
 * website_url?: string,
 * sub?: string,
 * }
 */
class AuthUser
{

    public function __construct(
        public int $id,
        public string $username,
        public string $name,
        public string $email,
        public ?string $picture_url = null,
        public ?string $location = null,
        public ?string $bio = null,
        public ?string $website_url = null,
        // only for OIDC
        public ?string $sub = null,
    )
    {}

    /**
     * @param LoginUserArray $data
     */
    public static function fromArray(array $data) : self
    {
        return new self(
            id: $data['id'],
            username: $data['username'],
            name: $data['name'],
            email: $data['email'],
            picture_url: $data['picture_url'] ?? null,
            location: $data['location'] ?? null,
            bio: $data['bio'] ?? null,
            website_url: $data['website_url'] ?? null,
            sub: $data['sub'] ?? null,
        );
    }


    public static function fromId(int $id) : self
    {

    }

}