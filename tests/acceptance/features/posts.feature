Feature: Posts

    Scenario: Listing all posts
        When I request "GET /posts"
        Then I get a "200" response
        And the "data" property exists
        And the "data" property is an array
        And scope into the first "data" property
            And the properties exist:
                """
                id
                category_id
                title
                slug
                body
                created_at
                """
            And the "id" property is an integer
            And the "category_id" property is an integer
        And reset scope
        And the "cursor" property is an object
        And scope into the "cursor" property
            And the "current" property is a integer equalling "0"
            And the "next" property is a integer equalling "8"
            And the "count" property is a integer equalling "8"

    Scenario: Listing all posts with embed category
        When I request "GET /posts?embed=category"
        Then I get a "200" response
        And the "data" property exists
        And the "data" property is an array
        And scope into the first "data" property
            And the properties exist:
                """
                id
                category_id
                title
                slug
                body
                created_at
                category
                """
            And the "id" property is an integer
            And the "category_id" property is an integer
            And scope into the parent "category.data" property
                And the properties exist:
                """
                id
                name
                slug
                """

    Scenario: Listing all posts with embed tags
        When I request "GET /posts?embed=tags"
        Then I get a "200" response
        And the "data" property exists
        And the "data" property is an array
        And scope into the first "data" property
            And the properties exist:
                """
                id
                category_id
                title
                slug
                body
                created_at
                tags
                """
            And the "id" property is an integer
            And the "category_id" property is an integer


