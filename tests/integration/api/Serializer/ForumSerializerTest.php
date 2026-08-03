<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Tests\integration\api\Serializer;

use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;

class ForumSerializerTest extends TestCase
{
    use RetrievesAuthorizedUsers;

    public function setUp(): void
    {
        parent::setUp();

        $this->prepareDatabase([
            'users' => [
                $this->normalUser(),
            ],
        ]);
    }

    public function userDataProvider()
    {
        return [
            [null],
            [1],
            [2],
        ];
    }

    /**
     * @dataProvider userDataProvider
     *
     * @test
     */
    public function forum_relations_are_not_accidentally_serialized(?int $userId)
    {
        $response = $this->send(
            $this->request('GET', '/api', [
                'authenticatedAs' => $userId,
            ])
        );

        $this->assertEquals(200, $response->getStatusCode());

        $document = json_decode($response->getBody()->getContents(), true);

        $this->assertEquals('forums', $document['data']['type']);

        $attributes = $document['data']['attributes'];

        $this->assertArrayHasKey('welcomeTitle', $attributes);
        $this->assertArrayNotHasKey('actor', $attributes);
        $this->assertArrayNotHasKey('groups', $attributes);

        $relationships = $document['data']['relationships'];

        $this->assertArrayHasKey('groups', $relationships);

        if ($userId) {
            $this->assertArrayHasKey('actor', $relationships);
        } else {
            $this->assertArrayNotHasKey('actor', $relationships);
        }
    }
}
