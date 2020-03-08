<departments>
{
	let $x := doc("reed.xml")//course
	for $dep in distinct-values(doc("reed.xml")//course/subj)
	let $flag := count(doc("reed.xml")//course[subj=$dep])
	return <department>
	{
		<department-code>{$dep}</department-code>,
		<no-of-courses>{$flag}</no-of-courses>
	}</department>
}
</departments>

