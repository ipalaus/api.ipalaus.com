Feature: Tags

    Scenario: Listing all tags
        When I request "GET /tags"
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

    Scenario: Finding a specific tag
        When I request "GET /tags/php"
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

    Scenario: Impossible to find an unexisten tag
        When I request "GET /tags/isern"
        Then I get a "404" response
