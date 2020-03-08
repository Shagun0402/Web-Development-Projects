<courses>
{
	for $a in doc("reed.xml")//course
	where $a/subj = 'MATH'
	and $a/place/building = 'LIB'
	and $a/place/room = '204'
	return <course> {
						$a/title,
						$a/instructor,
						$a/time/start_time,
						$a/time/end_time
					} </course>
}</courses>