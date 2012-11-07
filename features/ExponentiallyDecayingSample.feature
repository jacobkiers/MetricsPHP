Feature: Exponentially Decaying Sample

    Scenario: an exponentially decaying sample of 100 out of 1000 elements
        Given an exponentially decaying sample with size 100 and alpha value 0.99
        And receiving 1000 elements
        Then its size should be 100
        And its snapshot size should be 100
        And all snapshot values should be between 0 and 1000

    Scenario: an exponentially decaying sample of 100 out of 10 elements
        Given an exponentially decaying sample with size 100 and alpha value 0.99
        And receiving 10 elements
        Then its size should be 10
        And its snapshot size should be 10
        And all snapshot values should be between 0 and 10

    Scenario: an exponentially decaying sample of 1000 out of 100 elements
        Given an exponentially decaying sample with size 1000 and alpha value 0.01
        And receiving 100 elements
        Then its size should be 100
        And its snapshot size should be 100
        And all snapshot values should be between 0 and 100

    Scenario: long periods of inactivity do not corrupt the sampling state
        Given an exponentially decaying sample with size 10 and alpha value 0.015
        And having a manual clock
        And receiving 1000 elements starting at 1000 every 100 milliseconds
        Then its snapshot size should be 10
        And all snapshot values should be between 1000 and 2000

        And the clock moves 15 hours
        And receiving 1 element starting at 2000
        And its snapshot size should be 2
        And all snapshot values should be between 1000 and 3000

        And receiving 1000 elements starting at 3000 every 100 milliseconds
        And its snapshot size should be 10
        And all snapshot values should be between 3000 and 4000
