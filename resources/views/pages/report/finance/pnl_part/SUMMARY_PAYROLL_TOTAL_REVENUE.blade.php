<tr class="fixed" style="background: #eaeaea">
    <td class="text-center" colspan="0"><b>Total Payroll as % of Total Revenue</b></td>
    <td class="text-center"><b>{{ isset($data['ACTUAL_CURRENT']) ? number_format($data['ACTUAL_CURRENT'], 2)."%" : '-' }}</b></td>
    <td class="text-center"><b>-</b></td>
    <td class="text-center"><b>{{ isset($data['LAST_MONTH_CURRENT']) ? number_format($data['LAST_MONTH_CURRENT'], 2)."%" : '-' }}</b></td>
    <td class="text-center"><b>-</b></td>
    <td class="text-center"><b>{{ isset($data['VARIANCE_CURRENT']) ? number_format($data['VARIANCE_CURRENT'], 2)."%" : '-' }}</b></td>
    <td class="text-center"><b>-</b></td>
    <td class="text-center"><b>{{ isset($data['LAST_YEAR_CURRENT']) ? number_format($data['LAST_YEAR_CURRENT'], 2)."%" : '-' }}</b></td>
    <td class="text-center"><b>-</b></td>

    <td class="text-center"><b>{{ isset($data['ACTUAL_YTD']) ? number_format($data['ACTUAL_YTD'], 2)."%" : '-' }}</b></td>
    <td class="text-center"><b>-</b></td>
    <td class="text-center"><b>{{ isset($data['LAST_MONTH_YTD']) ? number_format($data['LAST_MONTH_YTD'], 2)."%" : '-' }}</b></td>
    <td class="text-center"><b>-</b></td>
    <td class="text-center"><b>{{ isset($data['VARIANCE_YTD']) ? number_format($data['VARIANCE_YTD'], 2)."%" : '-' }}</b></td>
    <td class="text-center"><b>-</b></td>
    <td class="text-center"><b>{{ isset($data['LAST_YEAR_YTD']) ? number_format($data['LAST_YEAR_YTD'], 2)."%" : '-' }}</b></td>
    <td class="text-center"><b>-</b></td>
</tr>