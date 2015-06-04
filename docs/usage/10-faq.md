# FAQ

Some questions you might have had:

**Q:** Why do you use container if dependency inversion principle isn't
implemented and nearly all of your components depend on specific other
components, not on interfaces?  
**A:** Container has been added to the project not to implement dependency
inversion principle, but to add additional layer in objects creation and reuse,
that's all. And yes, it sucks.

**Q:** Why the heck on earth do you comment in tests?  
**A:** A: i love when everything's right and B: i believe that tests should be
treated exactly like regular classes, because they do mean a lot and any
reporting tool will need a lot of editing anyway. 
