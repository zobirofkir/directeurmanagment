import FooterComponent from '@/components/FooterComponent'
import React from 'react'

const Layout = ({children}) => {
  return (
    <>
        <section>
            {children}
        </section>
        <FooterComponent />
    </>
  )
}

export default Layout
