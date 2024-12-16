<!-- <div class="day-view-table">
    <div class="pds-tabs-wrapper pds-screen-only">
        <div class="pds-tabs" role="tablist">
            <ul class="day-view-week-nav pds-gap-xs pds-screen-only pds-w-full js-week-nav">
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/16/5123637" aria-selected="true" aria-controls="panel-0" class="pds-tab pds-w-full test-Monday pds-tab-selected is-today  " data-analytics-element-id="timesheet-navigate-day-Monday">
                        <div>
                            M<span>on</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/17/5123637" aria-selected="false" aria-controls="panel-1" class="pds-tab pds-w-full test-Tuesday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Tuesday">
                        <div>
                            T<span>ue</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/18/5123637" aria-selected="false" aria-controls="panel-2" class="pds-tab pds-w-full test-Wednesday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Wednesday">
                        <div>
                            W<span>ed</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/19/5123637" aria-selected="false" aria-controls="panel-3" class="pds-tab pds-w-full test-Thursday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Thursday">
                        <div>
                            T<span>hu</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/20/5123637" aria-selected="false" aria-controls="panel-4" class="pds-tab pds-w-full test-Friday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Friday">
                        <div>
                            F<span>ri</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/21/5123637" aria-selected="false" aria-controls="panel-5" class="pds-tab pds-w-full test-Saturday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Saturday">
                        <div>
                            S<span>at</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/22/5123637" aria-selected="false" aria-controls="panel-6" class="pds-tab pds-w-full test-Sunday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Sunday">
                        <div>
                            S<span>un</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>

                <li id="day-view-week-nav-total" class="pds-text-right pds-px-xs">
                    Week total
                    <div class="pds-text-sm test-week-total">0:00</div>
                </li>
            </ul>
        </div>
    </div>

    <table id="day-view-entries">
        <tbody class="js-day-view-entry-list" data-test-day="2024-12-16" hidden=""></tbody>
        <tfoot>
            <tr class="pds-h4 js-day-view-summary"></tr>
        </tfoot>
    </table>

    <div id="timesheets-empty" class="js-empty-view">
        <div class="pds-empty pds-position-relative pds-my-md">
            <div class="pds-screen-only">
                <div class="pds-py-xxl">“It’s always about timing. If it’s too soon, no one understands. If it’s too late, everyone’s forgotten.”<br aria-hidden="true" class="pds-show@md">– Anna Wintour</div>
            </div>
            <div class="pds-print-only">No time tracked for this week!</div>
        </div>
    </div>


    <div class="pds-flex-list pds-justify-between pds-gap-sm pds-items-start pds-mt-md timesheet-bottom">
        <div class="js-timesheet-footer"><button type="button" data-analytics-element-id="timesheet-copy-rows" class="pds-button pds-screen-only">Copy rows from most recent timesheet</button></div>
        <div class="js-timesheet-approval">
            <form method="POST" action="/daily/review" class="button-and-confirmation test-approval-button"><input type="hidden" name="authenticity_token" value="6uJGzcWIEJS_8G6Rg_EKBViYI51W0AwWk1BcNIoJGV-yHv7d3lFoA4FvdLPNvtWYPkFqCC8Rn5vPOLFsaqTbQg"><input type="hidden" name="return_to" value="/time"><input type="hidden" name="of_user" value="5123637"><input type="hidden" name="submitted_date" value="351"><input type="hidden" name="submitted_date_year" value="2024"><input type="hidden" name="period_begin" value="351"><input type="hidden" name="period_begin_year" value="2024"><input type="hidden" name="from_timesheet_beta" value="true"><input type="hidden" name="from_screen" value="daily"><button data-analytics-element-id="timesheet-submit-for-approval" type="button" class="pds-button">Submit week for approval</button>
                <div hidden="" id="approval-disabled-tooltip" class="pds-tooltip-sm">Sorry, timesheet approval has been temporarily disabled. Please try again in a few minutes.</div>
            </form>
        </div>
    </div>
</div> -->
<div class="day-view-table">
    <div class="pds-tabs-wrapper pds-screen-only">
        <div class="pds-tabs" role="tablist">
            <ul class="day-view-week-nav pds-gap-xs pds-screen-only pds-w-full js-week-nav">
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/16/5123637" aria-selected="true" aria-controls="panel-0" class="pds-tab pds-w-full test-Monday pds-tab-selected is-today  has-completion-signal" data-tooltip="" data-tooltip-delay="500" data-tooltip-hide-on-hover="" aria-describedby="has-completion-signal-tooltip-Monday" data-analytics-element-id="timesheet-navigate-day-Monday">
                        <div>
                            M<span>on</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            1:00
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-label="You have time tracked on this day">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                    </button>
                    <div id="has-completion-signal-tooltip-Monday" class="pds-tooltip-sm" data-particles="" hidden="">
                        <strong>Nice job!</strong> You’ve tracked time on 16 Dec 2024.
                    </div>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/17/5123637" aria-selected="false" aria-controls="panel-1" class="pds-tab pds-w-full test-Tuesday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Tuesday">
                        <div>
                            T<span>ue</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/18/5123637" aria-selected="false" aria-controls="panel-2" class="pds-tab pds-w-full test-Wednesday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Wednesday">
                        <div>
                            W<span>ed</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/19/5123637" aria-selected="false" aria-controls="panel-3" class="pds-tab pds-w-full test-Thursday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Thursday">
                        <div>
                            T<span>hu</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/20/5123637" aria-selected="false" aria-controls="panel-4" class="pds-tab pds-w-full test-Friday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Friday">
                        <div>
                            F<span>ri</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/21/5123637" aria-selected="false" aria-controls="panel-5" class="pds-tab pds-w-full test-Saturday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Saturday">
                        <div>
                            S<span>at</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>
                <li>
                    <button type="button" role="tab" href="/time/day/2024/12/22/5123637" aria-selected="false" aria-controls="panel-6" class="pds-tab pds-w-full test-Sunday  is-not-today  " data-analytics-element-id="timesheet-navigate-day-Sunday">
                        <div>
                            S<span>un</span>
                        </div>
                        <div class="pds-display-flex pds-items-center pds-gap-xs pds-text-sm">
                            0:00
                        </div>
                    </button>
                </li>

                <li id="day-view-week-nav-total" class="pds-text-right pds-px-xs">
                    Week total
                    <div class="pds-text-sm test-week-total">1:00</div>
                </li>
            </ul>
        </div>
    </div>

    <table id="day-view-entries">
        <tbody class="js-day-view-entry-list" data-test-day="2024-12-16">
            <tr id="timesheet_day_entry_2543508825" data-analytics-day-entry-id="2543508825" class="day-view-entry test-entry-2543508825">
                <td>
                    <div class="">

                        <div class="entry-details">
                            <div class="entry-project">Budget Webform</div>
                            <div class="entry-client">
                                <span class="pds-show@md">(</span>
                                Budget Webform
                                <span class="pds-show@md">)</span>
                            </div>
                            <div class="entry-task">Design</div>

                        </div>
                    </div>
                </td>

                <td class="entry-time">
                    1:00
                </td>

                <td class="entry-actions pds-pl-0 pds-screen-only">
                    <div class="pds-flex-list pds-gap-sm pds-flex-list@xs-stretch pds-justify-end">
                        <button type="button" id="start-button-2543508825" class="pds-button pds-button-lg entry-toggle-timer js-start-timer" data-analytics-element-id="timesheet-start-timer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span>Start</span>
                        </button>


                        <button class="pds-button pds-button-sm js-edit-entry" title="Edit entry" type="button" data-analytics-element-id="timesheet-entry-edit">
                            Edit
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="pds-h4 js-day-view-summary">
                <td class="pds-text-right pds-weight-normal">Total:</td>
                <td class="pds-text-right">1:00</td>
                <td class="pds-screen-only"></td>
            </tr>
        </tfoot>
    </table>

    <div id="timesheets-empty" class="js-empty-view"></div>


    <div class="pds-flex-list pds-justify-between pds-gap-sm pds-items-start pds-mt-md timesheet-bottom">
        <div class="js-timesheet-footer"></div>
        <div class="js-timesheet-approval">
            <form method="POST" action="/daily/review" class="button-and-confirmation test-approval-button"><input type="hidden" name="authenticity_token" value="EYmz60ciYQESaiiXKc2csdpTbc2XxraXSwx1nhyUCEVJdQv7XPsZliz1MrVngkMsvIokWO4HJRoXZJjG_DnKWA"><input type="hidden" name="return_to" value="/time/day/2024/12/16/5123637"><input type="hidden" name="of_user" value="5123637"><input type="hidden" name="submitted_date" value="351"><input type="hidden" name="submitted_date_year" value="2024"><input type="hidden" name="period_begin" value="351"><input type="hidden" name="period_begin_year" value="2024"><input type="hidden" name="from_timesheet_beta" value="true"><input type="hidden" name="from_screen" value="daily"><button data-analytics-element-id="timesheet-submit-for-approval" type="button" class="pds-button">Submit week for approval</button>
                <div hidden="" id="approval-disabled-tooltip" class="pds-tooltip-sm">Sorry, timesheet approval has been temporarily disabled. Please try again in a few minutes.</div>
            </form>
        </div>
    </div>
</div>