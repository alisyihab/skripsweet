<table>
    <thead>
        <tr>
            <th colspan="2"> 
                <strong>Laporan Keuangan {{ $month }} - {{ $year }}</strong>
            </th>
        </tr>
        <tr>
            <th colspan="2"></th>
        </tr>
        <tr>
            <th><strong>Bulan</strong></th>
            <th><strong>Pemasukan</strong></th>
            <th><strong>Pengeluaran</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($finace as $row)
            <tr aria-colspan="2">
                <td>{{ $row['date'] }}</td>
                <td>Rp. {{ number_format($row['debit']) }}</td>
                <td>Rp. {{ number_format($row['credit']) }}</td>
            </tr>
            <tr></tr>
            <tr aria-colspan="2">
                <td><strong>Laba </strong></td>
                <td> &nbsp; </td>
                <td>
                    Rp. {{ $row['debit'] - $row['credit'] }} 
                </td>
            </tr>
        @endforeach
    </tbody>
</table>