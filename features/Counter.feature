Feature: Counter

    Scenario: counter is initialized 0
        Given a counter
        Then the count is 0

    Scenario: counter increment by one
        Given a counter
        And it is incremented by 1
        Then the count is 1

    Scenario: counter increment by twelve
        Given a counter
        And it is incremented by 12
        Then the count is 12

    Scenario: counter decrement by one
        Given a counter
        And it is decremented by 1
        Then the count is -1

    Scenario: counter decrement by twelve
        Given a counter
        And it is decremented by 12
        Then the count is -12

    Scenario: counter is cleared
        Given a counter
        And it is incremented by 3
        And it is cleared
        Then the count is 0
