Feature: Exponentially Weighted Moving Average
    is an average that gives more priority
    to events that happened more recently.

    Scenario: a 1-minute EWMA with a value of three
        has a rate of 0.6 events/sec after the first tick
        has a rate of 0.22072766 events/sec after 1 minute
        etc.

        Given a 1-minute EWMA
        And receiving an update with value 3
        And receiving a tick
        Then the rate is 0.6

        Given one minute elapses (1 minutes total)
        Then the rate is 0.22072766

        Given one minute elapses (2 minutes total)
        Then the rate is 0.08120117

        Given one minute elapses (3 minutes total)
        Then the rate is 0.02987224

        Given one minute elapses (4 minutes total)
        Then the rate is 0.01098938

        Given one minute elapses (5 minutes total)
        Then the rate is 0.00404277

        Given one minute elapses (6 minutes total)
        Then the rate is 0.00148725

        Given one minute elapses (7 minutes total)
        Then the rate is 0.00054713

        Given one minute elapses (8 minutes total)
        Then the rate is 0.00020128

        Given one minute elapses (9 minutes total)
        Then the rate is 0.00007405

        Given one minute elapses (10 minutes total)
        Then the rate is 0.00002724

        Given one minute elapses (11 minutes total)
        Then the rate is 0.00001002

        Given one minute elapses (12 minutes total)
        Then the rate is 0.00000369

        Given one minute elapses (13 minutes total)
        Then the rate is 0.00000136

        Given one minute elapses (14 minutes total)
        Then the rate is 0.00000050

        Given one minute elapses (15 minutes total)
        Then the rate is 0.00000018

    Scenario: A 5-minute EWMA

        Given a 5-minute EWMA
        And receiving an update with value 3
        And receiving a tick
        Then the rate is 0.6

        Given one minute elapses (1 minutes total)
        Then the rate is 0.49123845

        Given one minute elapses (2 minutes total)
        Then the rate is 0.40219203

        Given one minute elapses (3 minutes total)
        Then the rate is 0.32928698

        Given one minute elapses (4 minutes total)
        Then the rate is 0.26959738

        Given one minute elapses (5 minutes total)
        Then the rate is 0.22072766

        Given one minute elapses (6 minutes total)
        Then the rate is 0.18071653

        Given one minute elapses (7 minutes total)
        Then the rate is 0.14795818

        Given one minute elapses (8 minutes total)
        Then the rate is 0.12113791

        Given one minute elapses (9 minutes total)
        Then the rate is 0.09917933

        Given one minute elapses (10 minutes total)
        Then the rate is 0.08120117

        Given one minute elapses (11 minutes total)
        Then the rate is 0.06648190

        Given one minute elapses (12 minutes total)
        Then the rate is 0.05443077

        Given one minute elapses (13 minutes total)
        Then the rate is 0.04456415

        Given one minute elapses (14 minutes total)
        Then the rate is 0.03648604

        Given one minute elapses (15 minutes total)
        Then the rate is 0.02987224
