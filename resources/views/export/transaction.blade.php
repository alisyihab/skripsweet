<table>
    <thead>
        <tr>
            <th colspan="2"><strong>Laporan Transaksi {{ $month }} - {{ $year }}</strong></th>
        </tr>
        <tr>
            <th colspan="2"></th>
        </tr>
        <tr>
            <th>Tanggal</th>
            <th>Pemasukan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transaction as $row)
            <tr>
                <td>{{ $row['date'] }}</td>
                <td>Rp {{ number_format($row['total']) }}</td>
            </tr>
        @endforeach
        <tr>
	        <th colspan="2"></th>
        </tr>
        <tr>
	        <td>
		        <strong>
			        Total Pemasukan Transaksi {{ $month }} - {{ $year }} :
		        </strong>
	        </td>
	        @foreach($total as $row)
	            <td>
		            <strong>Rp. {{ number_format($row->count) }}</strong>
	            </td>
	        @endforeach
        </tr>
    </tbody>
</table>
