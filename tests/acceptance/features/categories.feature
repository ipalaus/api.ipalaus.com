Feature: Categories

    Scenario: Listing all categories
        When I request "GET /categories"
        Then I get a "200" response
        And the "data" property exists
        And the "data" property is an array
        And scope into the first "data" property
            And the properties exist:
                """
                id
                name
                slug
                """
            And the "id" property is an integer
        And reset scope

    Scenario: Finding a specific category
        When I request "GET /categories/web-development"
        Then I get a "200" response
        And the "data" property exists
        And scope into the "data" property
            And the properties exist:
                """
                id
                name
                slug
                """
            And the "id" property is an integer

    Scenario: Impossible to find an unexisten category
        When I request "GET /categories/isern"
        Then I get a "404" response
