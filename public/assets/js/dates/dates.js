export function formatIsoDate(isoDate, format) {
    const date = new Date(isoDate)
    const weekdays = ['dl.', 'dt.', 'dc.', 'dj.', 'dv.', 'ds.', 'dg.']
    const months = ['gen.', 'febr.', 'març', 'abr.', 'maig', 'juny', 'jul.', 'ag.', 'set.', 'oct.', 'nov.', 'des.']
    const map = {
      '%w': weekdays[date.getDay()],
      '%d': ('0' + date.getDate()).slice(-2),
      '%m': ('0' + (date.getMonth() + 1)).slice(-2),
      '%b': months[date.getMonth()],
      '%Y': date.getFullYear()
    }
    return format.replace(/%[wdbmY]/g, (match) => map[match])
  }

// const dateFormat = (date) => {const [datePart, timePart] = date.split(' '); const [year, month, day]=datePart.split('-');return `${day}-${month}-${year}`};
export const formatDate = (date) => {return new Date(date).toLocaleDateString('ca-AD', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}

// console.log("formatted date 2025-03-19:", formatDate('2025-03-19', '%w %d %b %Y'))
// const formatNumber = (nr, mn=3, mx=3) => new Intl.NumberFormat('fr-FR', {minimumFractionDigits: mn, maximumFractionDigits: mx, useGrouping: true}).format(nr)
// console.log(formatNumber(3333.45777754))