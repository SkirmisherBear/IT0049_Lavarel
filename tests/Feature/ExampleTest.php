<?php

test('the home page sends guests to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
