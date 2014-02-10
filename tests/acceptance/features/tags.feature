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
                """
            And the "id" property is an integer
        And reset scope
