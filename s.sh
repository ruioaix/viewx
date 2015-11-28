#!/bin/bash
for (( i = 2; i < 10; i++ )); do
	cp App/Adjust_001 App/Adjust_00${i}
done

for (( i = 10; i < 100; i++ )); do
	cp App/Adjust_001 App/Adjust_0${i}
done
